<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 10px 35px; 
        line-height: 1.45;
        font-size: 13px;
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
        font-size: 17px;
        font-weight: bold;
        margin: 0;
        padding: 0;
        line-height: 1.1;
    }

    .kop-text-sub {
        font-size: 14px;
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

<body>

<!-- ==================== KOP SURAT ==================== -->
<table class="kop-table">
    <tr>
        <td style="width: 65px; text-align: left; vertical-align: top;">
            <img src="{{ public_path('assets/logo_unila.jpg') }}" class="kop-logo">
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
    $nomorSurat = 'P4GN-UNILA/' . $laporan->id . '/' . date('Y');
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
<p>Hormat kami,<br><br><br>
    Petugas Satgas P4GN<br>
    Universitas Lampung
</p>

<div class="page-break"></div>


<!-- ==================== HALAMAN 2 ==================== -->
<table class="kop-table">
    <tr>
        <td style="width: 65px;"><img src="{{ public_path('assets/logo_unila.jpg') }}" class="kop-logo"></td>
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
    <tr><td class="label">Jenis Kasus</td><td>: {{ $laporan->jenis_narkoba }}</td></tr>
</table>

<div class="section-title">Kronologi</div>
<p style="text-align: justify;">{{ $laporan->kronologi }}</p>

<div class="section-title">Foto Lokasi</div>

@php
    $foto = public_path('storage/' . $laporan->foto_lokasi);
@endphp

@if ($laporan->foto_lokasi && file_exists($foto))
    <img src="file://{{ realpath($foto) }}" width="350">
@else
    <p><i>Tidak ada foto lokasi / file tidak ditemukan.</i></p>
@endif


</body>
</html>
