<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\User;
use App\Models\Roles;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\System\LogActivityService;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function getDataTableUser(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['profile.line'])->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('line_name', function ($user) {
                    return $user->profile?->line?->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->rawColumns([
                    'action'
                ])
                ->make(true);
        }
    }

    public function search(Request $request)
    {
        $term = $request->input('q');
        $users = User::where('fullname', 'LIKE', "%{$term}%")
                    ->whereNull('line_id') // opsional filter
                    ->limit(20)
                    ->get(['id', 'fullname']);

        return response()->json($users);
    }

    // public function create()
    // {
    //     $allRoles = Roles::all();
    //     $allLines = Line::all();
    //     return response()->json([
    //         'all_roles' => $allRoles,
    //         'all_lines'  => $allLines
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $userData = $request->validate([
    //         'fullname'     => 'required|string|max:255',
    //         'compCode'     => 'required|string|max:255',
    //         'employeId'    => 'required|string|unique:users,employeId',
    //         'empTypeGroup' => 'required|string',
    //         'email'        => 'required|email|unique:users,email',
    //         'email_backup' => 'nullable|email',
    //         'phone'        => 'nullable|string',
    //         'jobLvl'       => 'required|exists:roles,name',
    //         'line_id'      => 'nullable|exists:lines,id',
    //         'groupKode'    => 'required|string|max:255',
    //         'groupName'    => 'required|string',
    //     ]);

    //     $userData['jobTitle'] = $userData['jobLvl'];
    //     $userData['password'] = Hash::make('password');

    //     try {
    //         $user = User::create($userData);

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Tambah User',
    //                 'catatan' => 'Berhasil menambah user',
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User Has Been Created',
    //             'redirect' => route('admin.user.index')
    //         ]);
    //     } catch (\Throwable $th) {

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Tambah User',
    //                 'catatan' => $th->getMessage(),
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => $th->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function edit($id)
    // {
    //     $user = User::findOrFail($id);
    //     $lines = Line::all();
    //     $roles = Roles::all();
    //     return response()->json([
    //         'user' => $user,
    //         'lines' => $lines,
    //         'roles' => $roles,
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //    $userData = $request->validate([
    //         'fullname'     => 'required|string|max:255',
    //         'compCode'     => 'required|string|max:255',
    //         'employeId'    => 'required|string|unique:users,employeId,' . $id,
    //         'empTypeGroup' => 'required|string',
    //         'email'        => 'required|email|unique:users,email,' . $id,
    //         'email_backup' => 'nullable|email',
    //         'phone'        => 'nullable|string',
    //         'jobLvl'       => 'required|exists:roles,name',
    //         'jobTitle'     => 'required|string|max:255',
    //         'line_id'      => 'nullable|exists:lines,id',
    //         'groupKode'    => 'required|string|max:255',
    //         'groupName'    => 'required|string',
    //     ]);

    //     try {
    //         $user = User::findOrFail($id);
    //         $user->update($userData);

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Edit User',
    //                 'catatan' => 'Berhasil mengubah data user',
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User Has Been Updated',
    //             'redirect' => route('admin.user.index')
    //         ]);
    //     } catch (\Throwable $th) {

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Edit User',
    //                 'catatan' => $th->getMessage(),
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => $th->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function destroy(Request $request)
    // {
    //     $id = $request->input('id');
    //     try {
    //         $user = User::findOrFail($id);
    //         $user->delete();

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Hapus User',
    //                 'catatan' => 'Berhasil menghapus user',
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User Has Been Deleted',
    //         ]);
    //     } catch (\Throwable $th) {

    //         if (Auth::check()) {
    //             $user = Auth::user()->email;
    //             (new LogActivityService())->handle([
    //                 'perusahaan' => '-',
    //                 'user' => strtoupper($user),
    //                 'tindakan' => 'Hapus User',
    //                 'catatan' => $th->getMessage(),
    //             ]);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => $th->getMessage(),
    //             'redirect' => route('admin.user.index') 
    //         ]);
    //     }
    // }

}
