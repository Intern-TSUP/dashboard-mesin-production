<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Mesin;
use App\Models\Proses;
use App\Models\Line;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Services\System\LogActivityService;

class MesinController extends Controller
{

    public function index()
    {
        $all_proses = Proses::orderBy('name', 'asc')->get();

        $lines = Line::with('mesins.proses')->get();
        return view('v1.mesin.index', compact('all_proses', 'lines'));
    }

    public function getDataTableMesin(Request $request)
    {
        if ($request->ajax()) {
            $query = Mesin::with(['proses', 'line']);

            $userLine = auth()->user()->profile?->line;
            if ($userLine) {
                $query->where('line_id', $userLine->id);
            }

            // Terapkan filter Line jika ada
            if ($request->filled('filter_lines')) {
                $query->where('line_id', $request->filter_lines);
            }

            // Terapkan filter Proses jika ada
            if ($request->filled('filter_proses')) {
                $query->whereHas('proses', function($q) use ($request) {
                    $q->where('proses.id', $request->filter_proses);
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('proses_name', function ($mesin) {
                    if ($mesin->proses->isEmpty()) {
                        return '-';
                    }
                    return $mesin->proses->map(function($item) {
                        return '<span class="badge badge-light m-1">' . $item->name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('line_name', function ($mesin) {
                    return $mesin->line ? $mesin->line->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $detailBtn = '<button class="btn btn-sm btn-icon btn-light-info me-2" onclick="showDetail(\'' . $row->id . '\')" title="View Details"><i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></button>';
                    $editBtn = '<button class="btn btn-sm btn-icon btn-light-warning me-2" onclick="editRuang(\'' . $row->id . '\')" title="Edit"><i class="ki-duotone ki-notepad-edit fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></button>';
                    $deleteBtn = '<button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteRuang(\'' . $row->id . '\')" title="Delete"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></button>';
                    return $detailBtn . $editBtn . $deleteBtn;
                })
                ->editColumn('kapasitas', function($mesin) {
                    $kapasitas = $mesin->kapasitas;
                    if ($kapasitas) {
                        $kapasitas = $kapasitas . ' Liter';
                    } else {
                        $kapasitas = '-';
                    }
                    return '<span class="text-muted fw-bold">' . $kapasitas . '</span>';
                })
                ->editColumn('speed', function($mesin) {
                    $speed = $mesin->speed;
                    if ($speed) {
                        $speed = $speed . ' RPM';
                    } else {
                        $speed = '-';
                    }
                    return '<span class="text-muted fw-bold">' . $speed . '</span>';
                })
                ->editColumn('updated_at', function($mesin) {
                    return $mesin->tanggalUpdate;
                })
                ->rawColumns([
                    'action', 'proses_name', 'kapasitas', 'speed', 'line_name', 'updated_at'
                ])
                ->make(true);
        }
    }

    public function create()
    {
        $userLine = auth()->user()->profile?->line;
        $all_proses = Proses::all();
        $all_line = Line::all();
        return response()->json([
            'all_proses' => $all_proses,
            'all_line' => $all_line,
            'userLine' => $userLine,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'line_id'          => 'required|exists:lines,id',
            'kodeMesin'         => 'required|string|unique:mesins,kodeMesin',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
            'proses_ids'        => 'required|array',
            'keterangan'        => 'nullable|string',
            'link_kualifikasi'   => 'nullable|url',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // handle image upload if exists
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $path = $file->storeAs('mesin_images', $fileName, 'public');
            $validatedData['image'] = $path;
        }

        try {
            $mesin = Mesin::create([
                'line_id'           => $validatedData['line_id'],
                'kodeMesin'         => $validatedData['kodeMesin'],
                'name'              => $validatedData['name'],
                'kapasitas'         => $validatedData['kapasitas'] ?? null,
                'speed'             => $validatedData['speed'] ?? null,
                'jumlahOperator'    => $validatedData['jumlahOperator'],
                'keterangan'        => $validatedData['keterangan'] ?? null,
                'link_kualifikasi'   => $validatedData['link_kualifikasi'] ?? null,
                'image'             => $validatedData['image'] ?? null,
            ]);

            $mesin->proses()->attach($request->proses_ids);

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Tambah Mesin',
                'catatan' => 'Berhasil menambah data mesin ' . $mesin->name,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Created',
                'redirect' => route('v1.mesin.index')
            ]);

        } catch (\Throwable $th) { 
            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Tambah Mesin',
                'catatan' => $th->getMessage() . ' ' . $mesin->name,
            ]);

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $userLine = auth()->user()->profile?->line;
        $mesin = Mesin::with('proses', 'line')->findOrFail($id);
        $allProses = Proses::all();
        return response()->json([
            'userLine' => $userLine,
            'mesin' => $mesin,
            'all_proses' => $allProses,
        ]);
    }

    public function update(Request $request, $id)
    {
        $mesin = Mesin::findOrFail($id);

        $validatedData = $request->validate([
            'line_id'           => 'required|exists:lines,id',
            'proses_ids'        => 'required|array',
            'kodeMesin'         => 'required|string',
            'name'              => 'required|string|max:255',
            'kapasitas'         => 'nullable|string',
            'speed'             => 'nullable|string',
            'jumlahOperator'    => 'required|integer',
            'keterangan'        => 'nullable|string',
            'link_kualifikasi'   => 'nullable|url',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // handle image upload if exists
        if ($request->hasFile('image')) {
            if ($mesin->image) {
                Storage::disk('public')->delete($mesin->image);
            }
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $path = $file->storeAs('mesin_images', $fileName, 'public');
            $validatedData['image'] = $path;
        }
        else {
            $validatedData['image'] = $mesin->image;
        }

        try {
            $mesin->update([
                'line_id'        => $validatedData['line_id'],
                'kodeMesin'      => $validatedData['kodeMesin'],
                'name'           => $validatedData['name'],
                'kapasitas'      => $validatedData['kapasitas'] ?? null,
                'speed'          => $validatedData['speed'] ?? null,
                'jumlahOperator' => $validatedData['jumlahOperator'],
                'keterangan'     => $validatedData['keterangan'] ?? null,
                'link_kualifikasi'   => $validatedData['link_kualifikasi'] ?? null,
                'image'          => $validatedData['image'] ?? null,
            ]);

            $mesin->proses()->sync($request->proses_ids);

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Edit Mesin',
                'catatan' => 'Berhasil mengubah data mesin ' . $mesin->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Updated',
                'redirect' => route('v1.mesin.index')
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
            $mesin = Mesin::findOrFail($id);

            // hapus image jika ada
            if ($mesin->image) {
                Storage::disk('public')->delete($mesin->image);
            }
            
            $mesin->delete();

            $data = json_decode(auth()->user()->result, true);
            (new LogActivityService())->handle([
                'perusahaan' => strtoupper($data['CompName']),
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Hapus Mesin',
                'catatan' => 'Berhasil menghapus data mesin ' . $mesin->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Machine Has Been Deleted',
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
                'redirect' => route('v1.mesin.index') 
            ]);
        }
    }

}
