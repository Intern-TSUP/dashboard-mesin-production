<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mesin</title>
    <style>
        @page {
            margin: 10px;
        }

        /* Menghapus margin dan padding pada body agar konten menempel ke pinggir kertas */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0 !important;
            /* Menghapus margin */
            padding: 0;
            /* Menghapus padding */
            height: 100%;
            /* Agar konten mengisi seluruh halaman */
        }

        /* Membungkus seluruh konten dengan garis tepi */
        .container {
            border: 1px solid black;
            /* Garis tepi */
            height: 100%;
            /* Mengisi seluruh halaman */
            padding: 10px;
            /* Menghilangkan padding */
            box-sizing: border-box;
            /* Agar padding dan border tidak mempengaruhi ukuran */
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 70px;
            /* Ukuran logo */
        }

        .header h2 {
            margin: 0;
        }

        /* Sub-header */
        .sub-header {
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
            margin-top: -20px;
        }

        /* Footer */
        .footer {
            font-size: 10px;
            text-align: right;
            margin-top: 20px;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            /* Menghilangkan margin pada tabel */
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('assets/logo/Logo-Kalbe-&-BSB_Original.png') }}" alt="Logo">
            <h2>Laporan Data Mesin</h2>
        </div>

        <div class="sub-header">
            Line: {{ strtoupper($line_name) }} |
            Proses: {{ strtoupper($proses_name) }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Line</th>
                    <th>Proses</th>
                    <th>Kode Mesin</th>
                    <th>Nama Mesin</th>
                    <th>Kapasitas</th>
                    <th>Speed</th>
                    <th>Jumlah Operator</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                @php
                    $resultJson = optional($item->users)->result;
                    $resultArray = [];
                    if (!empty($resultJson) && is_string($resultJson)) {
                        $decoded = json_decode($resultJson, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $resultArray = $decoded;
                        }
                    }
                @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ strtoupper($item->line->name ?? 'N/A') }}</td>
                        <td>
                            @foreach ($item->proses as $proses)
                                {{-- Tampilkan setiap nama proses, dipisahkan koma --}}
                                {{ strtoupper($proses->name) }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>{{ strtoupper($item->kodeMesin) }}</td>
                        <td>{{ strtoupper($item->name) }}</td>
                        <td>{{ $item->kapasitas ? $item->kapasitas . ' Liter' : 'N/A' }}</td>
                        <td>{{ $item->speed ? $item->speed . ' RPM' : 'N/A' }}</td>
                        <td>{{ $item->jumlahOperator . ' Operator' }}</td>
                        <td>{{ $item->keterangan ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <script type="text/php">
            if (isset($pdf)) {
                // Posisi kiri bawah untuk teks halaman
                $x = $pdf->get_width() - 72; // Posisi horizontal (kiri ke kanan)
                $y = $pdf->get_height() - 30; // Posisi vertikal (dari bawah ke atas)
                
                // Teks halaman
                $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";

                // Mendapatkan font dari font metrics
                $font = $fontMetrics->get_font("helvetica", "italic");
                
                // Ukuran font
                $size = 7;

                // Warna hitam
                $color = array(0, 0, 0);

                // Spasi kata, spasi karakter, dan sudut teks (default)
                $word_space = 0.0;
                $char_space = 0.0;
                $angle = 0.0;

                // Menambahkan teks halaman
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

                // Menambahkan teks tanggal dan user di bawah kiri
                $dateText = "Cetakan Ke-{{$cetakanKe}} pada " . now()->format('d M Y H:i:s') . " oleh " . (auth()->user()->fullname ?? 'Sistem');
                
                // Posisi untuk teks tanggal dan user di bawah kiri
                $xDate = 18; // Posisi horizontal di kiri
                $yDate = $pdf->get_height() - 23; // Posisi vertikal di bawah
                
                // Menambahkan teks tanggal dan user
                $pdf->page_text($xDate, $yDate, $dateText, $font, $size, $color, $word_space, $char_space, $angle);

                // Menambahkan teks URL yang dicetak
                $urlText = "Dicetak dari halaman {{ $printedFromUrl }}";

                // Posisi untuk teks URL di bawah kiri
                $xUrl = 18;
                $yUrl = $pdf->get_height() - 30;
                
                // Menambahkan teks URL
                $pdf->page_text($xUrl, $yUrl, $urlText, $font, $size, $color, $word_space, $char_space, $angle);
            }
        </script>
        </div>
    </div>


</body>

</html>
