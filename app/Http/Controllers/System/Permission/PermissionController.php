<?php

namespace App\Http\Controllers\System\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\Roles;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function getDataTablePermission(Request $request)
    {
        if ($request->ajax()) {
            $query = Roles::query()->latest()->get();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('urls', function ($row) {
                    $count = $row->permission ? count($row->permission) : 0;
                    $urls = $count . ' Urls Access permission to user';
                    return $urls;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.permission.edit', $row->id) . '" class="btn btn-sm btn-icon btn-light-warning me-2"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span></i></a>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->rawColumns([
                    'urls',
                    'action'
                ])
                ->make(true);
        }
    }

    public function index()
    {
        return view('admin.permission.index');
    }

    public function create()
    {
        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.permission.create', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'jobLvl' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $role = Roles::create([
                'name' => $request->jobLvl
            ]);

            // Perbarui izin berdasarkan URL yang dipilih
            foreach ($request->input('urls', []) as $url) {
                $role->permission()->create(['url' => $url]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permission Has Been Created',
                'redirect' => route('admin.permission.index')
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
        $role = Roles::with('permission')->find($id);

        $routes = Route::getRoutes()->getRoutesByName();
        return view('admin.permission.edit', compact('role', 'routes'));
    }
    public function update(Request $request, $id)
    {
        $role = Roles::findOrFail($id);
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
            'redirect' => route('admin.permission.index')
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

        $role = Roles::find($id);

        // Hapus permissions melalui relasi
        $role->permission()->delete();

        // Hapus role
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }

}
