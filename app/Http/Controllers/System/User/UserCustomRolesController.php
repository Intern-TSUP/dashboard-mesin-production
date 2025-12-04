<?php

namespace App\Http\Controllers\System\User;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use App\Models\MaintenanceMode;
use App\Models\UserHasCustomRole;
use App\Services\System\LogActivityService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserCustomRolesController extends Controller
{
    public function getDataTable(Request $request, $role, $id)
    {
        $data = UserHasCustomRole::where('custom_role_id', $id)->latest()->get();

        return DataTables::of($data)->addIndexColumn()
            // ->editColumn('id_sub_department', function ($row) {
            //     if ($row->id_sub_department == null) {
            //         return 'N/A';
            //     } else {
            //         return $row->subDepartments->name.' - '.$row->subDepartments->departments->name;
            //     }
            // })
            ->addColumn('action', function ($row) {
                $btn = '<ul class="list-inline me-auto mb-0">
                                <li class="list-inline-item align-bottom" title="Edit">
                                    <button type="button" class="btn btn-sm btn-light-warning btn-icon editPost" 
                                        data-id="' . $row->id . '" 
                                        data-fullname="' . $row->fullname . '" 
                                        data-nik="' . $row->nik . '" 
                                        data-email="' . $row->email . '" 
                                        data-subdept="' . $row->id_sub_department . '" 
                                        data-phone="' . $row->phone . '" 
                                        data-dept="' . $row->dept . '" 
                                        style="border-radius: 7px;">
                                        <i class="ki-solid ki-pencil fs-2"></i>
                                    </button>
                                </li>
                                <li class="list-inline-item align-bottom"
                                    title="Delete">
                                    <button type="button" data-url="'.route('admin.user-has-custom-role.destroy', $row->id).'" class="btn btn-sm btn-light-danger btn-icon deletePost" style="border-radius: 7px;">
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
        $role = CustomRole::find($id);

        return view('admin.userHasCustomRole.index', compact('role'));
    }

    public function hrisGetEmployee(Request $request)
    {
        $app = MaintenanceMode::first();

        // $users = User::where('fullname', 'like', '%' . $request->q . '%')->get();
        $text = 'https://api-global-bundle-3scale-production.kalbe.co.id/globaluserprofile/api/UserProfile/ListUsers/Name/'.$request->q;

        $response = \Http::withHeaders([
            'Accept' => 'application/json',
            'X-API-VERSION' => '3',
            'X-API-TOKEN' => 'gagjc3ej3e8actv14ud8lqa9llbda4oaluvlrzkcirdqdml5rc9gdrpi4wq920of',
            'app_id' => '8a33cfd2',
            'app_key' => 'f7cce2000d6a3bbdb10e43871a19fadf'
        ])->get($text);
        $response = $response->json();

        $filtered = collect($response)->filter(function ($item) {
            return isset($item['EmpId']) &&
                preg_match('/^[0-9]+$/', $item['EmpId']);
        })->values();

        $data = [];
        foreach ($filtered as $item) {
            // code...
            $data[] = [
                'id' => $item['EmpId'],
                'fullname' => $item['EmployeeName'],
                'email' => $item['Email'],
                'phone' => $item['EmpHandPhone'] ?? 'NA',
                'jobTitle' => $item['JobTtlName'],
                'subDept' => $item['OrgName'],
                'dept' => $item['OrgGroupName'],
            ];
        }

        return response()->json($data, 200);
    }

    public function store(Request $request, $role, $id)
    {
        $role = CustomRole::find($id);
        $data = UserHasCustomRole::where('email', $request->email)->where('custom_role_id', $id)->first();
        if (!empty($data)) {
            // code...
            return response()->json([
                'success' => false,
                'message' => 'Account Sudah Terdaftar',
            ]);
        } else {
            $sub = $request->sub_department;

            if ($sub === 'NA') {
                $sub = null;
            }
            // code...
            UserHasCustomRole::create([
                'fullname' => $request->fullname,
                'nik' => $request->nik,
                'email' => $request->email,
                'dept' => $request->dept,
                'phone' => $request->phone,
                'id_sub_department' => $sub,
                'custom_role_id' => $id,
            ]);

            // Buat log activity
            $company = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($company['CompName'] ?? '-'),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'ADD',
                'catatan' => 'Berhasil Memberikan Role '.$role->name.' untuk "'.$request->email.'"',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account Berhasil didaftarkan',
            ]);
        }
    }

    public function destroy($id)
    {
        $data = UserHasCustomRole::find($id);
        if (empty($data)) {
            // code...
            return response()->json([
                'success' => false,
                'message' => 'Account Tidak Bisa dihapus',
            ]);
        }
        $data->delete();

        $company = json_decode(auth()->user()->result, true);
        (new LogActivityService())->handle([
            'perusahaan' => strtoupper($company['CompName'] ?? '-'),
            'user' => strtoupper(auth()->user()->email),
            'tindakan' => 'DELETE',
            'catatan' => 'Berhasil Menghapus Role untuk "'.$data->email.'"',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account Berhasil dihapus',
        ]);
    }

    public function edit($id)
    {
        $user = UserHasCustomRole::findOrFail($id);
        return response()->json($user);  // Return JSON untuk modal, mirip verifikator
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_fullname' => 'required|string|max:255',
            'edit_nik' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:user_has_custom_roles,email,' . $id,
        ]);
        $userCustomRole = UserHasCustomRole::findOrFail($id);
        $oldData = $userCustomRole->getOriginal();  // Simpan data lama untuk log
        $userCustomRole->fullname = $request->edit_fullname;
        $userCustomRole->nik = $request->edit_nik;
        $userCustomRole->email = $request->edit_email;
        $userCustomRole->id_sub_department = $request->edit_sub_department;
        $userCustomRole->save();

        $company = json_decode(auth()->user()->result, true);
        (new LogActivityService())->handle([
            'perusahaan' => strtoupper($company['CompName'] ?? '-'),
            'user' => strtoupper(auth()->user()->email),
            'tindakan' => 'UPDATE',
            'catatan' => 'Berhasil mengedit user "'.$userCustomRole->email.'"',
            'new_data' => $userCustomRole->getAttributes(),
            'old_data' => $oldData,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User custom role berhasil diperbarui.'
        ]);
    }
}
