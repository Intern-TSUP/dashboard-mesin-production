<?php

namespace App\Http\Controllers\System\Settings;

use App\Http\Controllers\Controller;
use App\Models\IdleTime;
use App\Models\MaintenanceMode;
use App\Services\LogService;
use App\Services\System\LogActivityService;
use Closure;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $maintenanceMode = MaintenanceMode::where('compCode', 'KF.01')->first();
        return view('admin.settings.index', compact('maintenanceMode'));
    }

    public function store(Request $request)
    {
        $time = $request->idle_time;
        $mode = $request->maintenance_mode;
        $url = $request->url_hris;
        $reason = $request->reason;

        $data = MaintenanceMode::first();
        if ($data->maintenance == false) {
            if ($mode == 'true') {
                (new LogActivityService)->handle([
                    'perusahaan' => 'Application',
                    'user' => 'SYSTEM',
                    'tindakan' => 'Enabled',
                    'catatan' => 'Started Maintenance Mode by System!'
                ]);
            }
        } elseif ($data->maintenance == true) {
            if ($mode == 'false') {
                (new LogActivityService)->handle([
                    'perusahaan' => 'Application',
                    'user' => 'SYSTEM',
                    'tindakan' => 'Disabled',
                    'catatan' => 'Finished Maintenance Mode by System!'
                ]);
            }
        }

        if (MaintenanceMode::where('compCode', 'KF.01')->first() === null) {
            MaintenanceMode::create([
                'compCode' => 'KF.01', 
                'maintenance' => $mode, 
                'url_hris' => $url,
                'reason' => $reason,
                'idle_time' => $time
            ]);
        } else {
            MaintenanceMode::where('compCode', 'KF.01')
                ->update([
                    'maintenance' => $mode,
                    'url_hris' => $url,
                    'reason' => $reason,
                    'idle_time' => $time
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updated Successfully!',
            'redirect' => route('admin.settings.index')
        ]);
    }
}
