<?php

namespace App\Http\Controllers\System\Permission;

use App\Http\Controllers\Controller;
use App\Models\PermissionsLine;
use App\Models\Line;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class PermissionLineController extends Controller
{
    public function getDataTablePermission(Request $request)
    {
        if ($request->ajax()) {
            $query = Line::withCount('users')->latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('urls', function ($row) {
                    $count = $row->permission ? count($row->permission) : 0;
                    $urls = $count . ' Urls Access permission to line';
                    return $urls;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.permissionLine.edit', $row->id);
                    $addUserUrl = route('admin.permissionLine.createUser', $row->id);

                    $editBtn = '<a href="' . $editUrl . '" class="btn btn-sm btn-icon btn-light-warning me-2" title="Manage Access"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
                    
                    $deleteBtn = '<button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')" title="Delete"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';

                    $addUserBtn = '<a href="' . $addUserUrl . '" class="btn btn-sm btn-light-primary d-flex align-items-center" title="Add User to Line">' .
                                '<i class="ki-duotone ki-user-add fs-2 me-1"></i> Add User' .
                                '</a>';

                    return '<div class="d-flex flex-row gap-2">' . $editBtn . $deleteBtn . $addUserBtn . '</div>';
                })
                ->editColumn('users_count', function ($row) {
                    return '<span class="badge badge-light-primary fs-7">' . $row->users_count . ' User</span>';
                })
                ->rawColumns([
                    'urls',
                    'action',
                    'users_count'
                ])
                ->make(true);
        }
    }

    public function index()
    {
        return view('admin.line.index');
    }

    public function create()
    {
        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.line.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'name' => 'required|string|unique:lines,name|max:255',
        ]);

        try {
            DB::beginTransaction();

            $line = Line::create([
                'name' => $request->name
            ]);

            // Perbarui izin berdasarkan URL yang dipilih
            foreach ($request->input('urls', []) as $url) {
                $line->permission()->create(['url' => $url]);
            }

            DB::commit();

            // $data = json_decode(auth()->user()->result, true);
            // (new LogActivityService())->handle([
            //     'perusahaan' => strtoupper($data['CompName']),
            //     'user' => strtoupper(auth()->user()->email),
            //     'tindakan' => 'Tambah Permission Line',
            //     'catatan' => 'Berhasil menambah permission line ' . $line['name'],
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission Line Has Been Created',
                'redirect' => route('admin.permissionLine.index')
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function edit($id)
    {
        $line = Line::with('permission')->find($id);

        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.line.edit', compact('line', 'routes'));
    }
    public function update(Request $request, $id)
    {
        $line = Line::findOrFail($id);
        $line->update(['name' => $request->line_id]);

        // Hapus permissions lama jika ada
        $line->permission()->delete();

        // Perbarui izin berdasarkan URL yang dipilih
        foreach ($request->input('urls', []) as $url) {
            $line->permission()->create(['url' => $url]);
        }

        // $data = json_decode(auth()->user()->result, true);
        // (new LogActivityService())->handle([
        //     'perusahaan' => strtoupper($data['CompName']),
        //     'user' => strtoupper(auth()->user()->email),
        //     'tindakan' => 'Edit Permission Line',
        //     'catatan' => 'Berhasil mengubah permission line ' . $line->name,
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Success Update',
            'redirect' => route('admin.permissionLine.index')
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.'
            ]);
        }

        $line = Line::find($id);

        // Hapus permissions melalui relasi
        $line->permission()->delete();

        // Hapus line
        $line->delete();

        // $data = json_decode(auth()->user()->result, true);
        // (new LogActivityService())->handle([
        //     'perusahaan' => strtoupper($data['CompName']),
        //     'user' => strtoupper(auth()->user()->email),
        //     'tindakan' => 'Hapus Permission Line',
        //     'catatan' => 'Berhasil menghapus permission line ' . $line->name,
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Line deleted successfully.'
        ]);
    }

    public function createUser($id)
    {
        $line = Line::findOrFail($id);
        return view('admin.line.user', compact('line'));  
    }

    public function getAvailableUsers(Request $request)
    {

        $search = $request->input('term');
        $availableUsers = User::where(function ($query) {
                $query->whereDoesntHave('profile')
                    ->orWhereHas('profile', function ($q) {
                        $q->whereNull('line_id');
                    });
            })
            ->where('fullname', 'ILIKE', "%{$search}%") 
            ->limit(10)
            ->get(['id', 'fullname as text']); 

        return response()->json($availableUsers);
    }

    public function getDataTableUser(Request $request)
    {
        if ($request->ajax()) {

            $lineId = $request->input('line_id');
            if ($lineId) {
                $query = User::with(['profile.line'])
                    ->whereHas('profile', function($q) use ($lineId) {
                        $q->where('line_id', $lineId);
                    });
            } else {
                $query = User::with(['profile.line']);
            }   

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('line_name', function ($user) {
                    return $user->profile?->line?->name ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $deleteBtn = '<button class="btn btn-sm btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')" title="Delete">Delete</button>';
                    return $deleteBtn;
                })
                ->rawColumns([
                    'action', 'line_name'
                ])
                ->make(true);
        }
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $lines = Line::all();
        return view('admin.line.editUser', compact('user', 'lines'));
    }   

    public function storeUser(Request $request, $id)
    {
        $line = Line::findOrFail($id);
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try{
            $user = User::findOrFail($validatedData['user_id']);
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['line_id' => $line->id]
            );

            // Log activity
            // $data = json_decode(auth()->user()->result, true);
            // (new LogActivityService())->handle([
            //     'perusahaan' => strtoupper($data['CompName']),
            //     'user' => strtoupper(auth()->user()->email),
            //     'tindakan' => 'Tambah User ke Permission Line',
            //     'catatan' => 'Berhasil menambah user ' . $user->fullname . ' ke permission line ' . $line->name,
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'User added to permission line successfully.',
                'redirect' => route('admin.permissionLine.index')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }   
    }

    public function destroyUser(Request $request)
    {
        $userId = $request->input('id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required.'
            ]);
        }

        $user = User::find($userId);

        if ($user && $user->profile) {
            // Hapus profile yang terkait dengan user
            $user->profile->delete();
        }

        // Log activity
        // $data = json_decode(auth()->user()->result, true);
        // (new LogActivityService())->handle([
        //     'perusahaan' => strtoupper($data['CompName']),
        //     'user' => strtoupper(auth()->user()->email),
        //     'tindakan' => 'Hapus User dari Permission Line',
        //     'catatan' => 'Berhasil menghapus user ' . $user->fullname . ' dari permission line',
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'User removed from permission line successfully.',
            'redirect' => route('admin.permissionLine.index')
        ]);
    }

}
