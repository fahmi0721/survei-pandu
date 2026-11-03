
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Survei</title>

    <style>
        @page {
            margin: 22mm 16mm 20mm 16mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
            line-height: 1.5;
        }

        h1, h2, h3 {
            margin: 0 0 10px;
        }

        h1 {
            font-size: 18px;
        }

        h2 {
            font-size: 13px;
            margin-top: 18px;
        }

        h3 {
            font-size: 11px;
            margin-top: 12px;
        }

        .center {
            text-align: center;
        }

        .muted {
            color: #666;
        }

        .header {
            text-align: center;
            padding-bottom: 12px;
            border-bottom: 2px solid #222;
            margin-bottom: 22px;
        }

        .header strong {
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 18px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 6px 7px;
            vertical-align: top;
        }

        th {
            background: #e9edf3;
            font-weight: bold;
        }

        tr {
            page-break-inside: avoid;
        }

        .info td {
            border: none;
            padding: 3px 5px;
        }

        .info td:first-child {
            width: 145px;
        }

        .section {
            margin-bottom: 20px;
        }

        .conclusion {
            background: #f3f5f7;
            padding: 10px;
            border-left: 3px solid #666;
            margin-bottom: 16px;
        }

        .page-break {
            page-break-before: always;
        }

        .respondent {
            page-break-before: always;
        }

        .section-title {
            font-weight: bold;
            margin-top: 14px;
        }

        .rank-one {
            background: #edf5ed;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: -12mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #777;
        }

        
        .header {
            border-bottom: 2px solid #222;
            padding-bottom: 10px;
            margin-bottom: 22px;
            text-align: left;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
            background: transparent;
        }

        .header-company {
            text-align: left;
        }

        .header-company strong {
            font-size: 15px;
        }

        .header-logo {
            width: 200px;
            text-align: right;
        }

        .header-logo img {
            width: 180px;
            height: auto;
            max-height: 65px;
        }

    </style>
</head>

<body>
@if($page === 1)
<div class="footer">
    PT INTAN SEJAHTERA UTAMA
    | Laporan Hasil Survei Perwira Pandu
</div>

{{-- HALAMAN AWAL --}}


<div class="header">
    <table class="header-table">
        <tr>
            <td class="header-company">
                <strong>PT INTAN SEJAHTERA UTAMA</strong>
                <div>Makassar, Sulawesi Selatan</div>
            </td>

            <td class="header-logo">
                <img
                    src="{{ public_path('images/new_logo.png') }}"
                    alt="Logo ISMA"
                >
            </td>
        </tr>
    </table>
</div>


<div class="center" style="margin-top: 65px;">
    <h1>LAPORAN HASIL SURVEI</h1>
    <h2>{{ $survey->title }}</h2>
    <p class="muted">
        Dicetak pada
        {{ $printedAt->format('d/m/Y H:i') }}
    </p>
</div>

<h2>INFORMASI LAPORAN</h2>

<table class="info">
    <tr>
        <td>Nama Survei</td>
        <td>: {{ $survey->title }}</td>
    </tr>
    <tr>
        <td>Jumlah Responden</td>
        <td>: {{ $summary['total_respondents'] }}</td>
    </tr>
    <tr>
        <td>Status Kerja</td>
        <td>: {{ $statusFilter
            ? ucwords(str_replace('_', ' ', $statusFilter))
            : 'Semua Status' }}</td>
    </tr>
    <tr>
        <td>Wilayah Kerja</td>
        <td>: {{ $workArea?->nama ?? 'Semua Wilayah' }}</td>
    </tr>
</table>

{{-- BAB I: RINGKASAN --}}

<div class="page-break"></div>

<h2>BAB I - RINGKASAN HASIL SURVEI</h2>

<table>
    <thead>
        <tr>
            <th>Keterangan</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Responden</td>
            <td>{{ $summary['total_respondents'] }}</td>
        </tr>
        <tr>
            <td>Status Aktif</td>
            <td>{{ $summary['status_counts']['aktif'] }}</td>
        </tr>
        <tr>
            <td>Status Tidak Aktif</td>
            <td>{{ $summary['status_counts']['tidak_aktif'] }}</td>
        </tr>
    </tbody>
</table>

<h2>BAB II - HASIL PRIORITAS SETIAP BAGIAN</h2>

@foreach($summary['sections'] as $index => $section)

    <div class="section">

        <h3>
            {{ $index + 1 }}.
            {{ $section['section_title'] }}
        </h3>

        <p>
            Jumlah responden dengan ranking 1:
            <strong>{{ $section['respondents'] }}</strong>
        </p>

        
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="30%">Pertanyaan / Aspek Prioritas</th>
                    <th width="35%">Deskripsi</th>
                    <th width="12%">Ranking 1</th>
                    <th width="18%">Persentase</th>
                </tr>
            </thead>

            <tbody>
                @foreach($section['questions'] as $i => $question)
                    <tr>
                        <td class="center">
                            {{ $i + 1 }}
                        </td>

                        <td>
                            {{ $question['question_text'] }}
                        </td>

                        <td>
                            {{ $question['description'] ?? '—' }}
                        </td>

                        <td class="center">
                            {{ $question['total_rank_1'] }}
                        </td>

                        <td class="center">
                            {{ number_format(
                                $question['percentage'],
                                2,
                                ',',
                                '.'
                            ) }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div class="conclusion">
            <strong>Kesimpulan:</strong>
            {{ $section['conclusion'] }}
        </div>

    </div>

@endforeach
@endif
{{-- LAMPIRAN --}}

<div class="page-break"></div>

<div class="center">
    <h2>LAMPIRAN</h2>
    <h3>REKAPITULASI JAWABAN PER RESPONDEN</h3>
</div>

<p>
    Lampiran ini memuat seluruh urutan prioritas
    yang diberikan masing-masing responden
    pada setiap bagian survei.
</p>

@forelse($respondents as $number => $respondent)

    <div class="respondent">

        <h2>
            RESPONDEN {{ (($page - 1) * $perPage) + $number + 1 }}
        </h2>

        <table class="info">
            <tr>
                <td>Kode Responden</td>
                <td>
                    : RSP-{{ str_pad(
                        $respondent['response_id'],
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) }}
                </td>
            </tr>

            <tr>
                <td>Status Kerja</td>
                <td>
                    : {{ ucwords(str_replace(
                        '_',
                        ' ',
                        $respondent['status']
                    )) }}
                </td>
            </tr>

            <tr>
                <td>Wilayah Kerja</td>
                <td>: {{ $respondent['wilayah'] }}</td>
            </tr>

            <tr>
                <td>Kelas Pandu</td>
                <td>: {{ $respondent['kelas_pandu'] }}</td>
            </tr>

            <tr>
                <td>Tanggal Pengisian</td>
                <td>
                    : {{ $respondent['submitted_at']
                        ? \Carbon\Carbon::parse(
                            $respondent['submitted_at']
                        )->format('d/m/Y H:i')
                        : '—' }}
                </td>
            </tr>
        </table>

        @forelse($respondent['sections'] as $sectionIndex => $section)

            <div class="section-title">
                Bagian {{ $sectionIndex + 1 }} -
                {{ $section['section_title'] }}
            </div>

            
            <table>
                <thead>
                    <tr>
                        <th width="12%">Ranking</th>
                        <th width="43%">Pertanyaan / Aspek Prioritas</th>
                        <th width="45%">Deskripsi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($section['answers'] as $answer)
                        <tr class="{{ $answer['ranking'] == 1
                            ? 'rank-one'
                            : '' }}">

                            <td class="center">
                                {{ $answer['ranking'] }}
                            </td>

                            <td>
                                {{ $answer['question_text'] }}
                            </td>

                            <td>
                                {{ $answer['description'] ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        @empty
            <p>Belum tersedia detail jawaban.</p>
        @endforelse

    </div>

@empty

    <p class="center">
        Belum ada responden pada filter yang dipilih.
    </p>

@endforelse

</body>
</html>
