<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\UserPrint;
use App\Services\LogService;
use App\Services\System\LogActivityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ActivityLog::select(['perusahaan', 'user', 'tindakan', 'catatan', 'waktu'])
                ->orderBy('created_at', 'DESC');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('DT_RowIndex', function ($row) use ($request) {
                    static $index = 1;
                    $start = $request->start ?? 0;
                    return $start + $index++;
                })
                ->editColumn('tanggal', function ($row) {
                    return Carbon::parse($row->waktu)->translatedFormat('l, d F Y H:i');
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $keyword = $request->search['value'];
                        $query->where(function ($q) use ($keyword) {
                            $q->where('user', 'like', "%{$keyword}%")
                                ->orWhere('tindakan', 'like', "%{$keyword}%")
                                ->orWhere('catatan', 'like', "%{$keyword}%");
                        });
                    }
                })
                ->orderColumn('tanggal', function ($query, $order) {
                    $query->orderBy('waktu', $order);
                })
                ->make(true);
        }

        return view("v1.auditTrail.index");
    }

    public function generatePdf(Request $request)
    {
        $tanggal_awal = $request->tanggal_awal . ' 00:00:00'; // Tambahkan jam awal
        $tanggal_akhir = $request->tanggal_akhir . ' 23:59:59'; // Tambahkan jam akhir

        $data = ActivityLog::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika tidak ada data, kembalikan respons JSON
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data untuk tanggal yang dipilih.'
            ], 404);
        }

        // Periksa apakah user sudah memiliki record di tabel user_print
        $model = ActivityLog::class;
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
                'tindakan' => 'Disetujui',
                'catatan' => 'Mencetak Data Report Ke-' . $cetakanKe . ' AuditTrail'
            ]);
        }


        $pdf = Pdf::loadView('v1.reports.audit-trail', compact('data', 'cetakanKe', 'tanggal_awal', 'tanggal_akhir'))
            ->setPaper('a4', 'landscape')
            ->setOption('dpi', 96);
        $text = 'Report_AuditTrail_' . time() . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $text, ['Content-Type' => 'application/pdf']);
    }
}
