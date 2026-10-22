@extends('layouts.survey')

@section('title', $survey->title)

@section('content')
<header class="survey-hero">
    <div class="eyebrow">SURVEI INTERNAL • PERWIRA PANDU</div>
    <h1>{{ $survey->title }}</h1>
    <p>{{ $survey->description ?: 'Memetakan prioritas kebutuhan dan kesejahteraan Perwira Pandu.' }}</p>
</header>

<main class="survey-main" id="publicSurveyApp" 
        data-survey-id="{{ $survey->id }}"  
        data-submit-url="{{ route('survey.public.submit', $survey) }}">
    <div id="intro" class="panel">
        <a href="{{ route('survey.public.index') }}"
        class="survey-back-link">

            <i class="ti ti-arrow-left"></i>
            Kembali ke Halaman Utama

        </a>
        <h2>Petunjuk Pengisian</h2>
        <p class="note-success">Survei terdiri dari <b>{{ $sectionData->count() }} bagian</b>, masing-masing berisi <b>10 aspek</b>. Susun semua aspek berdasarkan prioritas Anda.</p>
        <div class="note">
            <b>Ranking 1</b> = paling penting; <b>ranking 10</b> = paling tidak penting dibandingkan aspek lain dalam bagian yang sama.
            Gunakan tombol <b>↑ dan ↓</b> untuk mengubah urutan, atau geser melalui nomor ranking.
            Urutan awal adalah urutan master data, <b>bukan jawaban Anda</b>.
        </div>
        <h3>Informasi responden</h3>
        <div class="fieldgrid">
            <div class="field"><label for="status">Status Kerja</label><select id="status"><option value="">-- Pilih Status Kerja --</option><option value="aktif">Aktif</option><option value="tidak_aktif">Tidak Aktif</option></select></div>
            
            <div class="field"><label for="kelas">Kelas pandu (opsional)</label><select id="kelas"><option value="">-- Tidak diisi --</option><option value="Pandu Tingkat I">Pandu Tingkat I</option><option value="Pandu Tingkat I">Pandu Tingkat II</option></select></div>
            <div class="field">
                <label for="wilayah">Wilayah / lokasi penugasan (opsional)</label>
                <select id="wilayah" name="work_area_id">
                <option value="">-- Pilih Wilayah Kerja --</option>

                @foreach ($workAreas as $area)
                    <option value=" {{ $area->id }}">
                        {{ $area->nama }}
                    </option>
                @endforeach
            </select>
            </div>
            <div class="field"><label for="kode">Kode responden anonim (opsional)</label><input id="kode" placeholder="Jangan masukkan nama/NIK"></div>
        </div>
        <p class="small muted">Tahap integrasi ini masih berupa tampilan publik. Hasil belum tersimpan ke database; pada tahap berikutnya kita akan membuat endpoint pengiriman jawaban.</p>
        <div class="bottom"><span></span><button id="start" class="btn btn-primary-survey" type="button">Mulai survei →</button></div>
    </div>

    <div id="survey" class="panel hidden">
        <div class="topline"><span id="partlabel" class="eyebrow"></span><span id="counter" class="small muted"></span></div>
        <div class="progress"><div id="bar"></div></div>
        <div class="steps" id="steps"></div>
        <h2 id="parttitle"></h2>
        <p class="question" id="partquestion"></p>
        <div class="note">Nomor di kiri menunjukkan ranking saat ini. Periksa seluruh pilihan dan <b>konfirmasi</b> urutan sebelum melanjutkan.</div>
        <ol class="ranklist" id="ranklist"></ol>
        <label class="survey-confirm"><input id="confirm" type="checkbox"><span>Saya telah memeriksa dan menyetujui urutan prioritas pada bagian ini.</span></label>
        <div class="bottom"><button class="btn secondary" id="back" type="button">← Sebelumnya</button><button class="btn btn-primary-survey" id="next" type="button">Berikutnya →</button></div>
    </div>

    <div id="finish" class="panel hidden">
        <h2>Ringkasan Jawaban</h2>
        <div class="success">✓ Seluruh bagian telah dikonfirmasi. Periksa peringkat pertama setiap bagian.</div>
        <div id="summary" class="summary-list"></div>
        <div class="field"><label for="comment">Saran atau masukan tambahan (opsional)</label><textarea id="comment" placeholder="Masukan mengenai kebutuhan Perwira Pandu..."></textarea></div>
        <div class="warning small" id="submitNotice">
            <b>Perhatian:</b>
            Pastikan seluruh ranking sudah sesuai.
            Setelah menekan Simpan Jawaban, data akan dikirim
            ke sistem dan tidak dapat diedit melalui formulir ini.
        </div>
        <div class="bottom"><button class="btn secondary" id="edit" type="button">← Ubah jawaban</button>
        <div class="export-actions">
            <button type="button"
                class="btn btn-primary-survey"
                id="btnSubmitSurvey">

            <span id="submitText">
                Kirim Jawaban
            </span>

        </button>
        </div>
    </div>
    </div>
    <p class="foot">Survei Perwira Pandu • PT Intan Sejahtera Utama</p>
</main>
<script type="application/json" id="surveySectionsData">@json($sectionData)</script>
@endsection
