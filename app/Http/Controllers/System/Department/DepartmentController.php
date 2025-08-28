<?php

namespace App\Http\Controllers\System\Department;

use App\Http\Controllers\Controller;
use App\Models\DepartemenHris;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Services\System\LogActivityService;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('admin.department.index');
    }

    public function getDataTableDepartment(Request $request)
    {
        // if ($request->ajax()) {
            $query = DepartemenHris::where('OrgName', 'LIKE', '%CKR - Production Minicompany%')
                ->orderBy('OrgName', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        // }
    }

}
