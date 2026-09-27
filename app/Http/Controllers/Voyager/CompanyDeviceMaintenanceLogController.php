<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\CompanyDevice;
use App\Models\CompanyDeviceMaintenanceLog;
use Illuminate\Http\Request;

/**
 * Quick "log a repair" action used from the Company Device detail page
 * (battery swap, hard drive replacement, etc.) - see
 * resources/views/vendor/voyager/company-devices/read.blade.php. Reuses the
 * BREAD's own edit_company_devices permission rather than adding a new one.
 * Extends the app base Controller (not Illuminate\Routing\Controller) because
 * it needs $this->authorize() from the AuthorizesRequests trait.
 */
class CompanyDeviceMaintenanceLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.user');
    }

    public function store(Request $request, CompanyDevice $device)
    {
        $this->authorize('edit', $device);

        $data = $request->validate([
            'type' => 'nullable|in:' . implode(',', array_keys(CompanyDeviceMaintenanceLog::TYPES)),
            'note' => 'required|string|max:2000',
        ]);

        $device->maintenanceLogs()->create([
            'type'      => $data['type'] ?? null,
            'note'      => $data['note'],
            'logged_by' => auth()->id(),
            'logged_at' => now()->toDateString(),
        ]);

        return redirect()
            ->route('voyager.company-devices.show', $device->id)
            ->with('success', 'Maintenance logged.');
    }

    public function destroy(CompanyDeviceMaintenanceLog $log)
    {
        $this->authorize('edit', $log->device);

        $deviceId = $log->device_id;
        $log->delete();

        return redirect()
            ->route('voyager.company-devices.show', $deviceId)
            ->with('success', 'Maintenance entry removed.');
    }
}
