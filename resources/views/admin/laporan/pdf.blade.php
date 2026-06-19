<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

<style>
    body {
        font-family: 'Times New Roman', Times, serif, sans-serif;
        margin: 10px 35px; 
        line-height: 1.45;
        font-size: 15px;
    }

    .kop-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5px; 
    }

    .kop-table td {
        padding: 0; 
    }

    .kop-logo {
        width: 60px; 
        margin: 0;
        padding: 0;
    }

    .kop-text-title {
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        padding: 0;
        line-height: 1.1;
    }

    .kop-text-sub {
        font-size: 15px;
        font-weight: bold;
        margin: 0;
        padding: 0;
        line-height: 1.1;
    }

    .kop-desc {
        font-size: 10px;
        margin: 2px 0 0 0;
        padding: 0;
        font-style: italic;
        line-height: 1.1;
    }

    .kop-line {
        border-bottom: 2px solid #000;
        margin-top: 1px;
        margin-bottom: 15px;
    }

    table { width: 100%; }
    td { padding: 4px 2px; }

    .label { width: 28%; font-weight: bold; }

    .title-main {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .section-title {
        margin-top: 15px;
        margin-bottom: 6px;
        font-weight: bold;
        font-size: 15px;
    }

    .page-break { page-break-after: always; }
</style>

@php
    // Logo — pakai DOCUMENT_ROOT karena file ada di public_html/assets/
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/logo_unila.jpg';
    $logo = file_exists($logoPath)
        ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
        : null;

    // Foto lokasi — pakai storage_path langsung tanpa symlink
    $fotoSrc = null;
    if (!empty($laporan->foto_lokasi)) {
        $fotoPath = storage_path('app/public/' . $laporan->foto_lokasi);
        if (file_exists($fotoPath)) {
            $mime    = mime_content_type($fotoPath);
            $fotoSrc = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fotoPath));
        }
    }
@endphp

<body>

<!-- ==================== KOP SURAT ==================== -->
<table class="kop-table">
    <tr>
        <td style="width: 65px; text-align: left; vertical-align: top;">
            @if($logo)
                <img src="{{ $logo }}" class="kop-logo">
            @endif
        </td>

        <td style="text-align: center;">
            <p class="kop-text-title">UNIVERSITAS LAMPUNG</p>
            <p class="kop-text-sub">SATGAS P4GN</p>
            <p class="kop-desc">(Pencegahan, Pemberantasan, Penyalahgunaan dan Peredaran Gelap Narkoba)</p>
        </td>

        <td style="width: 40px;"></td>
    </tr>
</table>

<div class="kop-line"></div>


<!-- ==================== HALAMAN 1 ==================== -->
<div class="title-main">SURAT LAPORAN RESMI</div>

@php
    $nomorSurat = 'P4GN-UNILA/' . $laporan->kode_laporan . '/' . date('Y');
@endphp

<table>
    <tr><td class="label">Nomor</td><td>: {{ $nomorSurat }}</td></tr>
    <tr><td class="label">Lampiran</td><td>: 1 Laporan</td></tr>
    <tr><td class="label">Perihal</td><td>: Laporan Aduan/Pengaduan</td></tr>
</table>

<br>

<p>Kepada Yth,<br>
    <b>Ketua Satgas P4GN<br>Universitas Lampung</b><br>
    Di Tempat
</p>
<br>
<p style="text-align: justify;">
    Dengan hormat,<br>
    Bersama ini kami menyampaikan laporan resmi terkait aduan yang diterima oleh sistem Satgas P4GN Universitas Lampung.
    Laporan ini berisi identitas pelapor, lokasi kejadian, serta rincian permasalahan yang dilaporkan.<br><br>
    Adapun detail laporan terlampir pada berkas laporan sebagaimana mestinya.<br>
    Demikian surat laporan ini dibuat untuk diproses dan ditindaklanjuti sesuai prosedur yang berlaku.<br>
</p>
<br>
<p style="text-align: left; margin-left: 60%;">Hormat kami,<br><br><br><br>
    Petugas Satgas P4GN<br>
    Universitas Lampung
</p>

<div class="page-break"></div>


<!-- ==================== HALAMAN 2 ==================== -->
<table class="kop-table">
    <tr>
        <td style="width: 65px;">
            @if($logo)
                <img src="{{ $logo }}" class="kop-logo">
            @endif
        </td>
        <td style="text-align: center;"><p class="kop-text-sub">RINGKASAN LAPORAN</p></td>
        <td style="width: 40px;"></td>
    </tr>
</table>

<div class="kop-line"></div>

<table>
    <tr><td class="label">Nama Pelapor</td><td>: {{ $laporan->nama_pelapor }}</td></tr>
    <tr><td class="label">Nama Terlapor</td><td>: {{ $laporan->nama_terlapor }}</td></tr>
    <tr><td class="label">Tanggal Kejadian</td><td>: {{ $laporan->tanggal }}</td></tr>
    <tr><td class="label">Lokasi</td><td>: {{ $laporan->lokasi }}</td></tr>
    <tr><td class="label">Jenis Kasus</td><td>: {{ $laporan->jenis_kasus }}</td></tr>
</table>

<div class="section-title">Kronologi</div>
<p style="text-align: justify;">{{ $laporan->kronologi }}</p>

<div class="section-title">Foto Lokasi</div>

@if($fotoSrc)
    <img src="{{ $fotoSrc }}" width="350">
@else
    <p><i>Tidak ada foto lokasi / file tidak ditemukan.</i></p>
@endif


</body>
</html>