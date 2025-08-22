<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\MesinsExport;
use App\Models\Mesin;
use App\Models\Proses;
use App\Models\Roles;
use App\Models\Line;
use App\Models\UserPrint;
use App\Services\System\LogActivityService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $mesin = Mesin::with(['proses', 'line'])->get();
        $filter_lines = Line::orderBy('name', 'asc')->get();
        $filter_proses = Proses::orderBy('name', 'asc')->get();

        return view('v1.dashboard', compact('filter_lines', 'filter_proses', 'mesin'));
    }

    public function detail($id)
    {
        $mesin = Mesin::with(['proses', 'line'])->findOrFail($id);

        return response()->json([
            'mesin' => $mesin,
        ]);
    }

    public function generatePdf(Request $request)
    {
        $filter_lines = $request->filter_lines;
        $filter_proses = $request->filter_proses;

        $query = Mesin::with(['proses', 'line'])
            ->when($filter_lines, function ($query, $filter_lines) {
                return $query->where('line_id', $filter_lines);
            })
            ->when($filter_proses, function ($query, $filter_proses) {
                return $query->whereHas('proses', function ($q) use ($filter_proses) {
                    $q->where('proses.id', $filter_proses);
                });
            });

        $data = $query->orderBy('line_id', 'desc')->get();

        // Jika tidak ada data, kembalikan respons JSON
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data untuk tanggal yang dipilih.'
            ], 404);
        }

        $line_name = $filter_lines ? Line::find($filter_lines)->name : 'Semua Line';
        $proses_name = $filter_proses ? Proses::find($filter_proses)->name : 'Semua Proses';
        $printedFromUrl = url()->previous();

        // Periksa apakah user sudah memiliki record di tabel user_print
        $model = Mesin::class;
        $cetakanKe = UserPrint::query()
                        ->where('user_id', auth()->user()->id)
                        ->where('model', $model)
                        ->first();
        if (!$cetakanKe) {
            UserPrint::create([
                'user_id' => auth()->user()->id,
                'model' => $model,
                'print_count' => 1,
            ]);
            $cetakanKe = 1;
        } else {
            UserPrint::query()
                ->where('user_id', auth()->user()->id)
                ->where('model', $model)
                ->update(['print_count' => $cetakanKe->print_count + 1]);
            $cetakanKe = $cetakanKe->print_count + 1;
        }

        if (auth()->user()->jobLvl != 'Administrator') {
            (new LogActivityService)->handle([
                'perusahaan' => strtoupper(optional(json_decode(auth()->user()->result ?? '-'))->CompName) ?? '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Export PDF',
                'catatan' => 'Cetak PDF ke-' . $cetakanKe . ' data mesin pada ' . $line_name . ' & ' . $proses_name,
            ]);
        }

        else {
            (new LogActivityService)->handle([
                'perusahaan' => strtoupper(optional(json_decode(auth()->user()->result ?? '-'))->CompName) ?? '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Export PDF',
                'catatan' => 'Cetak PDF ke-' . $cetakanKe . ' data mesin pada ' . $line_name . ' & ' . $proses_name,
            ]);
        }

        $pdf = Pdf::loadView('v1.reports.list-machine', compact('data', 'cetakanKe', 'line_name', 'proses_name', 'printedFromUrl'))
            ->setPaper('a4', 'landscape')
            ->setOption('dpi', 96);
        $text = 'Data_Mesin_' . time() . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $text, ['Content-Type' => 'application/pdf']);
    }

    public function generateExcel(Request $request)
    {
        $filter_lines = $request->filter_lines;
        $filter_proses = $request->filter_proses;

        $query = Mesin::with(['proses', 'line'])
            ->when($filter_lines, function ($query, $filter_lines) {
                return $query->where('line_id', $filter_lines);
            })
            ->when($filter_proses, function ($query, $filter_proses) {
                return $query->whereHas('proses', function ($q) use ($filter_proses) {
                    $q->where('proses.id', $filter_proses);
                });
            });

        $data = $query->orderBy('line_id', 'desc')->get();

        // if ($data->isEmpty()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Tidak ada data untuk filter yang dipilih.'
        //     ], 404);
        // }

        $line_name = 'SEMUA LINE';
        if ($filter_lines) {
            $line = Line::find($filter_lines);
            if ($line) {
                $line_name = $line->name;
            }
        }

        $proses_name = 'SEMUA PROSES';
        if ($filter_proses) {
            $proses = Proses::find($filter_proses);
            if ($proses) {
                $proses_name = $proses->name;
            }
        }

        // $line_name = $filter_lines ? Line::find($filter_lines)->name : 'Semua Line';
        // $proses_name = $filter_proses ? Proses::find($filter_proses)->name : 'Semua Proses';
        $printedFromUrl = url()->current();

        $model = Mesin::class;
        $cetakanKe = UserPrint::query()
                        ->where('user_id', auth()->user()->id)
                        ->where('model', $model)
                        ->first();
        if (!$cetakanKe) {
            UserPrint::create([
                'user_id' => auth()->user()->id,
                'model' => $model,
                'print_count' => 1,
            ]);
            $cetakanKe = 1;
        } else {
            UserPrint::query()
                ->where('user_id', auth()->user()->id)
                ->where('model', $model)
                ->update(['print_count' => $cetakanKe->print_count + 1]);
            $cetakanKe = $cetakanKe->print_count + 1;
        }

        if (auth()->user()->jobLvl != 'Administrator') {
            (new LogActivityService)->handle([
                'perusahaan' => strtoupper(optional(json_decode(auth()->user()->result ?? '-'))->CompName) ?? '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Export Excel',
                'catatan' => 'Cetak Excel ke-' . $cetakanKe . ' data mesin pada ' . $line_name . ' & ' . $proses_name,
            ]);
        } else {
            (new LogActivityService)->handle([
                'perusahaan' => strtoupper(optional(json_decode(auth()->user()->result ?? '-'))->CompName) ?? '-',
                'user' => strtoupper(auth()->user()->email),
                'tindakan' => 'Export Excel',
                'catatan' => 'Cetak Excel ke-' . $cetakanKe . ' data mesin pada ' . $line_name . ' & ' . $proses_name,
            ]);
        }

        // Prepare data for Excel
        // $exportData = [];
        // foreach ($data as $item) {
        //     $exportData[] = [
        //         'Nama Mesin' => $item->name,
        //         'Line' => $item->line->name ?? '-',
        //         'Proses' => $item->proses->name ?? '-',
        //         'Tanggal Dibuat' => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '-',
        //     ];
        // }

        // Generate Excel file
        $filename = 'Data_Mesin_' . time() . '.xlsx';

        return Excel::download(new MesinsExport($data, $line_name, $proses_name, $cetakanKe, $printedFromUrl), $filename);
    }

}
