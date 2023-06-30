<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Jobs\EmailsHandlerJob;
use App\Models\Project;
use App\Models\ProjectTarget;
use App\Models\UserPayment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class DeveloperPaymentController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    /**
     * Override store method to send email before store
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $project = Project::find($request->project_id);
        $pt = ProjectTarget::find($request->project_target_id);
        EmailsHandlerJob::dispatch([
            'mail_name' => 'DeveloperPaymentRequest',
            'to' => Auth::user()->email,
            'subject' => 'Payment Request of '. Auth::user()->name,
            'developer_name' => Auth::user()->name,
            'project_name' => $project->name,
            'project_target_title' => $pt->title,
            'project_target_status' => $pt->status,
            'total_earning' => $request->total_earning,
            'dev_earning' => $request->dev_earning,
            'payable' => $request->payable,
            'paid' => $request->paid,
            'currency_current_rate' => $request->currency_current_rate,
            'fee' => $request->fee,
            'notes' => $request->notes,
            'is_payment_approve_req' => false,
        ]);

        return parent::store($request);
    }

    // POST BR(E)AD
    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        event(new BreadDataUpdated($dataType, $data));
        if ($data->status == UserPayment::APPROVED_STATUS) {
            EmailsHandlerJob::dispatch([
                'mail_name' => 'DeveloperPaymentRequest',
                'to' => $data->developer->email,
                'subject' => 'Your Payment Request Approved'. $data->title,
                'developer_name' => $data->developer->name,
                'project_name' => $data->project->name,
                'project_target_title' => $data->title,
                'project_target_status' => $data->status,
                'total_earning' => $data->total_earning,
                'dev_earning' => $data->dev_earning,
                'payable' => $data->payable,
                'paid' => $data->paid,
                'currency_current_rate' => $data->currency_current_rate,
                'fee' => $data->fee,
                'notes' => $data->notes,
                'is_payment_approve_req' => true,
            ]);

        }
        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }
}
