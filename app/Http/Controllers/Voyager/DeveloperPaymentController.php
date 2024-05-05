<?php

namespace App\Http\Controllers\Voyager;

use App\Jobs\EmailsHandlerJob;
use App\Models\User;
use App\Models\UserPayment;
use App\utils\traits\EmailTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;

class DeveloperPaymentController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    use EmailTrait;

    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        // Add the authenticated user's developer_id
        $data = $request->merge(['developer_id' => Auth::user()->id]);
        // Begin a transaction
        DB::beginTransaction();
        try {
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
            event(new BreadDataAdded($dataType, $data));
            $this->sendEmail($data);

            if ( auth() && auth()->user() && auth()->user()->email) {
                if (auth()->user()->email != 'ayubkhokar786@gmail.com') {
                    // Add an entry for "ayubkhokar786@gmail.com" after the data is saved
                    $this->addEntryForAyubKhokar($request, $data->id);
                }
            }

            DB::commit();


            if (!$request->has('_tagging')) {
                if (auth()->user()->can('browse', $data)) {
                    $redirect = redirect()->route("voyager.{$dataType->slug}.index");
                } else {
                    $redirect = redirect()->back();
                }

                return $redirect->with([
                    'message' => __('voyager::generic.successfully_added_new') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]);
            } else {
                return response()->json(['success' => true, 'data' => $data]);
            }
        } catch (\Exception $e) {
            // If an exception occurs, roll back the transaction
            DB::rollback();
            // Handle the exception as needed
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);

        }

    }

    private function addEntryForAyubKhokar($request, $firstPaymentId)
    {
        $ayubUser = User::where('email', 'ayubkhokhar786@gmail.com')->first();
        // Get references to the input fields by name from the request
        $totalEarning = $request->input('total_earning');
        $selectedClientSource = $request->input('client_source');
        $currentCurrencyRate = $request->input('currency_current_rate');

        if (!empty($totalEarning) && is_numeric($totalEarning)) {
            $devEarning = 0;

            if ($selectedClientSource === "fiverr") {
                // Calculate dev earning for Fiverr (total earning - 20%)
                $devEarning = $totalEarning * 0.8;
            } else if ($selectedClientSource === "upwork") {
                // Calculate dev earning for Upwork (total earning - 10%)
                $devEarning = $totalEarning * 0.9;
            } else if ($selectedClientSource === "payonner" || $selectedClientSource === "other") {
                // Assign total earning for Payonner and Other without deductions
                $devEarning = $totalEarning;
            }

            // Calculate percentage of employee
            $devNetEarning = $devEarning * $ayubUser->percentage ?? 0.03;

            // Calculate the payable amount by multiplying devNetEarning with the currency rate
            if ($currentCurrencyRate) {
                $payableAmount = $devNetEarning * $currentCurrencyRate;
            }


            $up = new UserPayment();
            $up->developer_id = $ayubUser->id;
            $up->income_id = $request->income_id;
            $up->project_id = $request->project_id;
            $up->project_target_id = $request->project_target_id;
            $up->client_source = $request->client_source;
            $up->total_earning = $totalEarning;

            $up->dev_earning = $devNetEarning;
            if ($currentCurrencyRate) {
                $up->payable = $payableAmount;
            }
            $up->fee = $request->fee;
            $up->currency_current_rate = $request->currency_current_rate;
//            $up->status = $request->status;
            $notes = $request->input('notes');
            $notes .= "\nAutomatically generated by system";
            $up->notes = $notes;
            $up->created_at = now();
            $up->updated_at = now();
            // Update the first entry with the second entry's ID
            $firstEntry = UserPayment::find($firstPaymentId);
            if ($firstEntry) {
                $up->second_entry_id = $firstEntry->id;
            }
            $up->generated_by_system =true;
            $up->save();
            $ayubPayment = UserPayment::with(['developer', 'project', 'projectTarget'])->find($up->id);
            $this->sendEmail($ayubPayment);


        }
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
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
            $this->sendEmail($data, true);

        }
        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function markUserPaymentPaid($id): \Illuminate\Http\RedirectResponse
    {
        // Find the UserPayment record by ID
        $userPayment = UserPayment::find($id);

        if (!$userPayment) {
            return redirect()->back()->with('error', 'Payment not found.');
        }

        $userPayment->paid = $userPayment->payable;
        $userPayment->status = UserPayment::APPROVED_STATUS;
        $userPayment->save();

        // Redirect the user
        return redirect()->back()->with('success', 'Payment marked as paid successfully.');
    }

}
