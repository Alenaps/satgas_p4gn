@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">BERANDA</h1>
    <p class="text-gray-600 mt-2">
        Selamat datang, 
        <span class="font-semibold text-green-600">{{ auth()->user()->nama }}</span>
    </p>
</div>

{{-- ================= CARD STATISTIK ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-5xl font-bold mb-2">{{ $totalLaporan }}</p>
                <p class="text-sm opacity-90">Total Laporan Masuk</p>
            </div>
            <i class="fas fa-exclamation-circle text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-5xl font-bold mb-2">{{ $totalKonseling }}</p>
                <p class="text-sm opacity-90">Total Sesi Konseling</p>
            </div>
            <i class="fas fa-comments text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-5xl font-bold mb-2">{{ $totalPublikasi }}</p>
                <p class="text-sm opacity-90">Total Publikasi</p>
            </div>
            <i class="fas fa-newspaper text-4xl opacity-30"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-5xl font-bold mb-2">{{ $totalUser }}</p>
                <p class="text-sm opacity-90">Total Pengguna</p>
            </div>
            <i class="fas fa-users text-4xl opacity-30"></i>
        </div>
    </div>

</div>

{{-- ================= GRID CHART ================= --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- BAR CHART --}}
    <div class="bg-white p-6 shadow-lg rounded-xl">
        <h2 class="font-bold text-lg mb-4 text-gray-800">
            <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
            Perbandingan Jenis Kasus Terlapor
        </h2>
        <canvas id="barChart"></canvas>
    </div>

    {{-- PIE CHART --}}
    <div class="bg-white p-6 shadow-lg rounded-xl">
        <h2 class="font-bold text-lg mb-4 text-gray-800">
            <i class="fas fa-chart-pie text-green-500 mr-2"></i>
            Komposisi Pengguna Konsulen
        </h2>
        <div class="flex justify-center items-center" style="max-width:300px;margin:auto">
            <canvas id="pieChart"></canvas>
        </div>
    </div>

</div>

{{-- LINE CHART --}}
<div class="bg-white p-6 shadow-lg rounded-xl">
    <h2 class="font-bold text-lg mb-4 text-gray-800">
        <i class="fas fa-chart-line text-purple-500 mr-2"></i>
        Aktivitas Konseling Per Bulan
    </h2>
    <canvas id="lineChart"></canvas>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------------- DATA DARI BLADE ----------------
        const kasusBulanIni    = @json($kasusBulanIni);
        const kasusBulanLalu   = @json($kasusBulanLalu);
        const roleLabels       = @json($userRole->keys());
        const roleData         = @json($userRole->values());
        const konselingBulanan = @json($konselingPerBulan);

    // ---------------- BAR CHART ----------------
    const labelsKasus = ['Pengguna', 'Pengedar', 'Kurir', 'Bandar'];
    const dataBulanIni = labelsKasus.map(k => kasusBulanIni[k] ?? 0);
    const dataBulanLalu = labelsKasus.map(k => kasusBulanLalu[k] ?? 0);

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: labelsKasus,
            datasets: [
                { label: 'Bulan Ini', data: dataBulanIni, backgroundColor: '#3B82F6', borderRadius: 5 },
                { label: 'Bulan Lalu', data: dataBulanLalu, backgroundColor: '#10B981', borderRadius: 5 }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    // ---------------- PIE CHART ----------------
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: roleLabels,
            datasets: [{
                data: roleData,
                backgroundColor: ['#EF4444', '#10B981', '#3B82F6'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            cutout: '65%'
        }
    });

    // ---------------- LINE CHART ----------------
    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const dataKonseling = bulan.map((_, i) => konselingBulanan[i+1] ?? 0);

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: bulan,
            datasets: [{
                label: 'Jumlah Konseling',
                data: dataKonseling,
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139,92,246,.15)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

});
</script>
@endsection
