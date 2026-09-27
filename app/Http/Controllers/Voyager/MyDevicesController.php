<?php

namespace App\Http\Controllers\Voyager;

use App\Models\CompanyDevice;
use App\Models\CompanyDeviceAssignment;
use Illuminate\Routing\Controller;

/**
 * Read-only "My Devices" page for developers - shows the company devices
 * currently assigned to the logged-in user plus their past handovers.
 * Management (add / assign / edit) stays in the Voyager BREADs, which are
 * limited to Administrator / admin / HR.
 */
class MyDevicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.user');
    }

    public function index()
    {
        $userId = auth()->id();

        $devices = CompanyDevice::with(['holder', 'maintenanceLogs'])
            ->where('assigned_to', $userId)
            ->orderBy('name')
            ->get();

        $history = CompanyDeviceAssignment::with(['device', 'assigner'])
            ->where('user_id', $userId)
            ->orderByDesc('assigned_on')
            ->get();

        return view('vendor.voyager.my-devices.index', compact('devices', 'history'));
    }
}
