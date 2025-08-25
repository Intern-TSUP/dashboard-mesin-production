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

}
