<?php

namespace App\Http\Controllers\System\LocalUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\System\LogActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class LocalUserController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.localUser.index');
    }

    public function getData(Request $request) {
        if ($request->ajax()) {
            $query = User::where('is_local', true)->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                        return '<div class="text-nowrap">
                                    <button class="btn btn-sm btn-icon btn-light-info me-2" onclick="resetPassword(this)" data-id="' . e($row->id) . '" title="reset password">
                                        <i class="ki-duotone ki-arrows-circle fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-warning me-2" onclick="editData(this)" data-id="' . e($row->id) . '" title="edit">
                                        <i class="ki-duotone ki-pencil fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-danger" onclick="deleteData(\'' . $row->id . '\')" title="delete">
                                        <i class="ki-duotone ki-trash fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                </div>';
                    })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function searchEmployee(Request $request)
    {
        $qRaw = trim((string) $request->q);
        $q    = mb_strtolower($qRaw);

        if (mb_strlen($q) < 4) {
            return response()->json([], 200);
        }

        $url = 'https://api-global-bundle-3scale-production.kalbe.co.id/globaluserprofile/api/UserProfile/ListUsers/Name/' . rawurlencode($qRaw);

        $resp = \Http::withHeaders([
                'Accept' => 'application/json',
                'X-API-VERSION' => '3',
                'X-API-TOKEN' => 'gagjc3ej3e8actv14ud8lqa9llbda4oaluvlrzkcirdqdml5rc9gdrpi4wq920of',
                'app_id' => '8a33cfd2',
                'app_key' => 'f7cce2000d6a3bbdb10e43871a19fadf',
            ])
            ->connectTimeout(5)
            ->timeout(20)
            ->get($url);

        $json = $resp->json();

        if (empty($json) || !is_array($json)) {
            return response()->json([], 200);
        }
        
        $collection = collect($json);

        $workdayData = $collection->filter(function ($item) {
            return strtoupper($item['DataSource'] ?? '') === 'WORKDAY';
        })->keyBy(function ($item) {
            return strtoupper(trim($item['EmployeeName'] ?? $item['Name'] ?? ''));
        });

        $prointData = $collection->filter(function ($item) {
            $ds = strtoupper($item['DataSource'] ?? '');
            return $ds === 'PROINT' || $ds === ''; 
        });

        $data = $prointData->map(function ($proint) use ($workdayData) {
            $nameKey = strtoupper(trim($proint['EmployeeName'] ?? $proint['Name'] ?? ''));

            $workday = $workdayData->get($nameKey) ?? $proint['WorkdayJobs'][0] ?? [];

            $getVal = function ($valProint, $valWorkday) {
                return (isset($valProint) && trim((string)$valProint) !== '') ? $valProint : $valWorkday;
            };

            $orgName = $getVal($proint['OrgName'] ?? '', $workday['OrgName'] ?? '');
            $formattedOrg = 'NA';

            if (!empty($orgName)) {
                $orgNameParts = explode(' - ', $orgName);
                $formattedOrg = 'KF BO ' . trim(end($orgNameParts));
            } elseif (!empty($proint['BUDetail']['Name'])) {
                $formattedOrg = 'KF BO ' . trim($proint['BUDetail']['Name']);
            }

            return [
                'compCode'     => $getVal($proint['CompCode'] ?? null, $workday['CompCode'] ?? null),
                'employeId'    => $getVal($proint['NIK'] ?? null, $workday['NIK'] ?? null),
                'fullname'     => $getVal($proint['Name'] ?? null, $workday['Name'] ?? null),
                'empTypeGroup' => $getVal($proint['EmpTypeGroup'] ?? null, $workday['EmpTypeGroup'] ?? null),
                'jobLvl'       => $getVal($proint['JobLvlName'] ?? null, $workday['JobLvlName'] ?? null),
                'jobTitle'     => $getVal($proint['JobTtlName'] ?? $proint['empJobTtl'] ?? null, $workday['JobTtlName'] ?? null),
                'deptKode'     => $getVal($proint['EmpOrg'] ?? null, $workday['EmpOrg'] ?? null),
                'groupKode'    => $getVal($proint['OrgGroup'] ?? $proint['DivCode'] ?? null, $workday['OrgGroup'] ?? null) ?: 'NA',
                'groupName'    => $formattedOrg,
            ];
        })
        ->unique(function ($item) {
            return strtoupper(trim($item['fullname']));
        })
        ->values()
        ->toArray();

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $existingLocalUser = User::where('employeId', trim($request->employeId))->where('is_local', true)->first();

        if ($existingLocalUser) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'User dengan NIK ' . $request->employeId . ' tersebut sudah terdaftar sebagai user lokal'
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $localUser = User::create([
                'compCode'       => $request->compCode,
                'employeId'      => $request->employeId,
                'fullname'       => $request->fullname,
                'empTypeGroup'   => $request->empTypeGroup,
                'email'          => trim(mb_strtolower($request->email)),
                'email_backup'   => null,
                'phone'          => null,
                'jobLvl'         => $request->jobLvl,
                'jobTitle'       => $request->jobTitle,
                'deptKode'       => $request->deptKode,
                'groupKode'      => $request->groupKode,
                'groupName'      => $request->groupName,
                'password'       => \Hash::make('kalbefarma'),
                'created_at'     => now(),
                'updated_at'     => now(),
                'is_local'       => true,
            ]);

            \DB::commit();

            (new LogActivityService())->handle([
                'perusahaan' => '-',
                'user'       => strtoupper(auth()->user()->email),
                'tindakan'   => 'Tambah Local User',
                'catatan'    => 'Berhasil menambah data user lokal: ' . $request->fullname,
            ]);

            return response()->json([
                'success'  => true,
                'status'   => 'success',
                'message'  => 'Data berhasil ditambahkan',
                'redirect' => route('admin.localUser.index') 
            ], 200);

        } catch (\Throwable $th) {
            \DB::rollBack();

            \Log::error("Gagal simpan user lokal: " . $th->getMessage());

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $th->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();

            $localUser = User::findOrFail($request->edit_id);

            $oldData = $localUser->toArray();
            
            $localUser->update([
                'compCode'   => $request->edit_compCode,
                'employeId' => $request->edit_employeId,
                'fullname'    => $request->edit_fullname,
                'email'       => trim(mb_strtolower($request->edit_email)),
                'jobLvl'     => $request->edit_jobLvl,
                'jobTitle'   => $request->edit_jobTitle,
                'deptKode'  => $request->edit_deptKode,
                'groupKode'  => $request->edit_groupKode,
                'groupName'  => $request->edit_groupName,
                'updated_at'  => now(),
            ]);

            \DB::commit();

            (new LogActivityService())->handle([
                'perusahaan' => '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Mengubah Local User',
                'catatan' => 'Berhasil mengubah data user lokal: ' . $request->edit_fullname,
            ]);

            return response()->json([
                'success'  => true,
                'status'   => 'success',
                'message'  => 'Data berhasil diubah',
                'redirect' => route('admin.localUser.index') 
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $th->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            \DB::beginTransaction();

            $localUser = User::findOrFail($request->id);

            $oldData = $localUser->toArray();
            
            $localUser->update([
                'password'       => \Hash::make('kalbefarma'),
            ]);

            \DB::commit();

            (new LogActivityService())->handle([
                'perusahaan' => '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Reset password Local User',
                'catatan' => 'Berhasil mereset password user lokal: ' . $localUser->fullname,
            ]);

            return response()->json([
                'success'  => true,
                'status'   => 'success',
                'message'  => 'Password berhasil direset ke default',
                'redirect' => route('admin.localUser.index') 
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $deletedUser = $user->toArray();

            $localUser = User::findOrFail($request->id);
            $user->delete();

            (new LogActivityService())->handle([
                'perusahaan' => '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Menghapus Local User',
                'catatan' => 'Berhasil menghapus user lokal: ' . $deletedUser['fullname'],
            ]);

            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus user'], 500);
        }
    }

    public function UpdatePassword(Request $request)
    {
        try {
            \DB::beginTransaction();

            $localUser = User::findOrFail(auth()->user()->id);

            $oldData = $localUser->toArray();
            
            $localUser->update([
                'password' => \Hash::make($request->password),
            ]);

            \DB::commit();

            (new LogActivityService())->handle([
                'perusahaan' => '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Mengubah Password',
                'catatan' => 'Berhasil mengubah password'
            ]);

            return response()->json([
                'success'  => true,
                'status'   => 'success',
                'message'  => 'Password berhasil diubah',
                'redirect' => url()->previous()
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $th->getMessage()
            ], 500);
        }
    }
}
