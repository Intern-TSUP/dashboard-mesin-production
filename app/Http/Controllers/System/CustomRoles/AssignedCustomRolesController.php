<?php

namespace App\Http\Controllers\System\CustomRoles;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use App\Models\MaintenanceMode;
use App\Models\UserHasCustomRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignedCustomRolesController extends Controller
{
    public function getDataTable(Request $request)
    {
        $data = UserHasCustomRole::latest()->get();

        return DataTables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<ul class="list-inline me-auto mb-0">
                            <li class="list-inline-item align-bottom"
                                title="Delete">
                                <button type="button" data-url="'.route('admin.roles.assigned.destroy', [$row->custom_role_id, $row->id]).'" class="btn btn-sm btn-light-danger btn-icon deletePost" style="border-radius: 7px;">
                                    <i class="ki-solid ki-trash fs-2"></i>
                                </button>
                            </li>
                        </ul>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index($role, $id)
    {
        $customRoles = CustomRole::find($id);

        return view('admin.roles.assigned.index', compact('customRoles'));
    }

    public function hrisGetEmployee(Request $request)
    {
        $app = MaintenanceMode::first();

        // $users = User::where('fullname', 'like', '%' . $request->q . '%')->get();
        $text = $app->url_hris.'v1/ListUsers/Name?SearchbyName='.$request->q;

        $response = \Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-Key' => 'SQA45CsPgqRCeyoO0ZzeKK6BFG1vpR1vy7r-gvPiEw4',
        ])->get($text);
        $response = $response->json();

        $data = [];
        foreach ($response as $item) {
            // code...
            $data[] = [
                'id' => $item['EmpID'],
                'fullname' => $item['EmployeeName'],
                'email' => $item['EmpEmail'],
                'phone' => $item['EmpHandPhone'] ?? 'NA',
                'jobTitle' => $item['JobTtlName'],
                'subDept' => $item['OrgName'],
                'dept' => $item['OrgGroupName'],
            ];
        }

        return response()->json($data, 200);
    }

    // public function store(Request $request)
    // {
    //     $data = UserRevisi::where('email', $request->email)->first();
    //     if (!empty($data)) {
    //         // code...
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Account Sudah Terdaftar',
    //         ]);
    //     } else {
    //         // code...
    //         UserRevisi::create([
    //             'fullname' => $request->fullname,
    //             'nik' => $request->nik,
    //             'email' => $request->email,
    //             'dept' => $request->dept,
    //             'phone' => $request->phone,
    //             'id_sub_department' => $request->sub_department,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Account Berhasil didaftarkan',
    //         ]);
    //     }
    // }

    // public function destroy($id)
    // {
    //     $data = UserRevisi::find($id);
    //     if (empty($data)) {
    //         // code...
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Account Tidak Bisa dihapus',
    //         ]);
    //     }

    //     $data->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Account Berhasil dihapus',
    //     ]);
    // }
}
