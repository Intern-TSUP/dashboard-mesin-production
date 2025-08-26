<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\DepartemenHris;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Services\System\LogActivityService;

class LineController extends Controller
{
    public function index()
    {
        return view('v1.line.index');
    }

    public function getDataTableLine(Request $request)
    {
        if ($request->ajax()) {
            $query = Line::with('departemen');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>
                        <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>';
                })
                ->editColumn('updated_at', function($line) {
                    return $line->tanggalUpdate;
                })
                ->rawColumns([
                    'action'
                ])
                ->make(true);
        }
    }

    public function  create()
    {
        $all_departemen = DepartemenHris::where('OrgName', 'LIKE', '%CKR - Production Minicompany%')
            ->orderBy('OrgName', 'asc')
            ->get();
        return response()->json([
            'all_departemen' => $all_departemen,
        ]);
    }

    public function store(Request $request)
    {
        $line = $request->validate([
            'empOrg'    => 'required|string',
            'name'      => 'required|string|unique:lines,name|max:255'
        ]);

        try {
            Line::create($line);

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Tambah Line',
                'catatan' => 'Berhasil menambah ' . $line['name'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Created',
                'redirect' => route('v1.line.index')
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $all_departemen = DepartemenHris::where('OrgName', 'LIKE', '%CKR - Production Minicompany%')
            ->orderBy('OrgName', 'asc')
            ->get();
        $lineData = Line::with('departemen')->findOrFail($id);
        return response()->json([
            'all_departemen'    => $all_departemen,
            'lineData'          => $lineData
        ]);
    }

    public function update(Request $request, $id)
    {
       $lineData = $request->validate([
            'empOrg'    => 'required|string',
            'name'      => 'required|string|unique:lines,name,' . $id . '|max:255',
        ]);

        try {
            $line = Line::findOrFail($id);
            $line->update($lineData);

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Edit Line',
                'catatan' => 'Berhasil mengubah ' . $line->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Updated',
                'redirect' => route('v1.line.index')
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        try {
            $line = Line::findOrFail($id);
            $line->delete();

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Hapus Line',
                'catatan' => 'Berhasil menghapus ' . $line->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Line Has Been Deleted',
            ]);
        } catch (\Throwable $th) {
            
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.line.index')
            ]);
        }
    }

}
