<?php

namespace App\Http\Controllers\System\CustomRoles;

use App\Http\Controllers\Controller;
use App\Models\CustomRole;
use App\Models\UserHasCustomRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomRolesController extends Controller
{
    public function getDataTable(Request $request)
    {
        $query = CustomRole::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('urls', function ($row) {
                $count = $row->permission ? count($row->permission) : 0;
                $urls = $count.' Urls Access permission to user';

                return $urls;
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('admin.roles.edit', $row->id).'" class="btn btn-sm btn-icon btn-light-warning me-2"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i></a>
                    <button class="btn btn-sm btn-icon btn-light-danger me-5" onclick="deleteRoles(\''.$row->id.'\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                |
                    <a href="'.route('admin.user-has-custom-role.index', [strtolower(str_replace(' ', '-', $row->name)), $row->id]).'" class="btn btn-light-info btn-sm ms-5">Tambahkan User</a>
                    ';
            })
            ->rawColumns([
                'urls',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        $routes = \Route::getRoutes()->getRoutesByName();

        return view('admin.roles.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'jobLvl' => 'required|string',
        ]);

        try {
            \DB::beginTransaction();

            $role = CustomRole::create([
                'name' => $request->jobLvl,
            ]);

            // Perbarui izin berdasarkan URL yang dipilih
            foreach ($request->input('urls', []) as $url) {
                $role->permission()->create(['url' => $url]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Roles Has Been Created',
                'redirect' => route('admin.roles.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            // throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $role = CustomRole::with('permission')->find($id);

        $routes = \Route::getRoutes()->getRoutesByName();

        return view('admin.roles.edit', compact('role', 'routes'));
    }

    public function update(Request $request, $id)
    {
        $role = CustomRole::findOrFail($id);
        $role->update(['name' => $request->jobLvl]);

        // Hapus permissions lama jika ada
        $role->permission()->delete();

        // Perbarui izin berdasarkan URL yang dipilih
        foreach ($request->input('urls', []) as $url) {
            $role->permission()->create(['url' => $url]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success Update',
            'redirect' => route('admin.roles.index'),
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $role = CustomRole::find($id);
        $userHasRoles = UserHasCustomRole::where('custom_role_id', $id)->delete();

        // Hapus permissions melalui relasi
        $role->permission()->delete();

        // Hapus role
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.',
        ]);
    }
}
