{{-- resources/views/profile/index.blade.php --}}

@php
    $user = auth()->user();
    $role = $user->role;
    
    switch($role) {
        case 'konsuli':  $layout = 'layouts.konsuli'; break;
        case 'konselor': $layout = 'layouts.konselor'; break;
        case 'admin':    $layout = 'layouts.admin'; break;
        default:         $layout = 'layouts.konsuli'; break;
    }
@endphp

@extends($layout)

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    {{-- Notifikasi Profil Belum Lengkap: Konselor --}}
    @if($user->role === 'konselor')
        @php
            $kp = $user->konselorProfile;
            $fieldKosong = [];
            if (!$kp || !$kp->nomor_lisensi)            $fieldKosong[] = 'Nomor Lisensi';
            if (!$kp || !$kp->spesialisasi)             $fieldKosong[] = 'Spesialisasi';
            if (!$kp || $kp->pengalaman_kerja === null)  $fieldKosong[] = 'Pengalaman Kerja';
            if (!$kp || !$kp->pendidikan_terakhir)      $fieldKosong[] = 'Pendidikan Terakhir';
            if (!$kp || !$kp->id_instansi)              $fieldKosong[] = 'Instansi';
            if (!$kp || !$kp->id_jabatan)               $fieldKosong[] = 'Jabatan';
            if (!$kp || !$kp->bio_singkat)              $fieldKosong[] = 'Bio Singkat';
        @endphp
        @if(count($fieldKosong) > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-4 flex items-start gap-3 shadow-sm">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 text-lg flex-shrink-0"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-800">Profil konselor Anda belum lengkap!</p>
                    <p class="text-xs text-yellow-700 mt-1">Field yang belum diisi: <span class="font-semibold">{{ implode(', ', $fieldKosong) }}</span></p>
                    <a href="{{ route('profile.edit') }}" class="inline-block mt-2 text-xs font-semibold text-yellow-800 underline hover:text-yellow-900">Lengkapi profil sekarang →</a>
                </div>
                <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700 flex-shrink-0"><i class="fas fa-times"></i></button>
            </div>
        @endif
    @endif

    {{-- Notifikasi Profil Belum Lengkap: Konsuli --}}
    @if($user->role === 'konsuli')
        @php
            $fieldKosongKonsuli = [];
            if (!$user->no_telp)           $fieldKosongKonsuli[] = 'No. Telepon';
            if (!$user->jenis_kelamin)     $fieldKosongKonsuli[] = 'Jenis Kelamin';
            if (!$user->status_sivitas_id) $fieldKosongKonsuli[] = 'Status Sivitas';
            if (!$user->unit_id)           $fieldKosongKonsuli[] = 'Unit / Fakultas';
            if (!$user->foto)              $fieldKosongKonsuli[] = 'Foto Profil';
        @endphp
        @if(count($fieldKosongKonsuli) > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-4 flex items-start gap-3 shadow-sm">
                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 text-lg flex-shrink-0"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-800">Profil Anda belum lengkap!</p>
                    <p class="text-xs text-yellow-700 mt-1">Field yang belum diisi: <span class="font-semibold">{{ implode(', ', $fieldKosongKonsuli) }}</span></p>
                    <a href="{{ route('profile.edit') }}" class="inline-block mt-2 text-xs font-semibold text-yellow-800 underline hover:text-yellow-900">Lengkapi profil sekarang →</a>
                </div>
                <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700 flex-shrink-0"><i class="fas fa-times"></i></button>
            </div>
        @endif
    @endif

    {{-- Profile Card --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-user-circle mr-2"></i> Profile Saya
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri - Foto --}}
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <div class="mb-4 flex justify-center">
                            @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Profile Picture"
                                     class="w-40 h-40 rounded-full object-cover border-4 border-green-500 shadow-lg">
                            @else
                                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border-4 border-green-500 shadow-lg flex items-center justify-center">
                                    <i class="fas fa-user text-gray-400 text-5xl"></i>
                                </div>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $user->nama }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $user->npm_nip }}</p>

                        <div class="flex justify-center gap-2 flex-wrap">
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->role === 'konsuli' && $user->statusSivitas)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">
                                    {{ $user->statusSivitas->nama }}
                                </span>
                            @endif
                        </div>

                        {{-- Progress konselor --}}
                        @if($user->role === 'konselor')
                            @php $persenLengkap = round((7 - count($fieldKosong)) / 7 * 100); @endphp
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Kelengkapan Profil</span>
                                    <span class="font-semibold {{ $persenLengkap == 100 ? 'text-green-600' : 'text-yellow-600' }}">{{ $persenLengkap }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all {{ $persenLengkap == 100 ? 'bg-green-500' : 'bg-yellow-400' }}" style="width: {{ $persenLengkap }}%"></div>
                                </div>
                            </div>
                        @endif

                        {{-- Progress konsuli --}}
                        @if($user->role === 'konsuli')
                            @php $persenKonsuli = round((5 - count($fieldKosongKonsuli)) / 5 * 100); @endphp
                            <div class="mt-4">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>Kelengkapan Profil</span>
                                    <span class="font-semibold {{ $persenKonsuli == 100 ? 'text-green-600' : 'text-yellow-600' }}">{{ $persenKonsuli }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all {{ $persenKonsuli == 100 ? 'bg-green-500' : 'bg-yellow-400' }}" style="width: {{ $persenKonsuli }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom Kanan - Data --}}
                <div class="lg:col-span-2">
                    <div class="space-y-3">

                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                            <p class="text-gray-800 font-medium">{{ $user->nama }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-2 text-sm"></i>{{ $user->email }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">NPM/NIP</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-id-card text-gray-400 mr-2 text-sm"></i>{{ $user->npm_nip ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No. Telepon</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-2 text-sm"></i>{{ $user->no_telp ?? '-' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                            <p class="text-gray-800 font-medium flex items-center">
                                <i class="fas fa-venus-mars text-gray-400 mr-2 text-sm"></i>{{ $user->jenis_kelamin ?? '-' }}
                            </p>
                        </div>

                        {{-- ===== DATA KONSULI ===== --}}
                        @if($user->role === 'konsuli')
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-xs font-bold text-green-700 uppercase tracking-wide mb-3 flex items-center gap-1">
                                    <i class="fas fa-user-graduate"></i> Data Sivitas
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Sivitas</label>
                                <p class="text-gray-800 font-medium flex items-center">
                                    <i class="fas fa-user-graduate text-gray-400 mr-2 text-sm"></i>
                                    @if($user->statusSivitas)
                                        {{ $user->statusSivitas->nama }}
                                    @else
                                        <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Kategori Unit + Nama Unit (tampilan saja) --}}
                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kategori Unit</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-layer-group text-gray-400 text-sm"></i>
                                    @if($user->unit && $user->unit->kategori_unit)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full
                                            {{ $user->unit->kategori_unit === 'Akademik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-200 text-gray-600' }}">
                                            <i class="fas {{ $user->unit->kategori_unit === 'Akademik' ? 'fa-graduation-cap' : 'fa-briefcase' }} text-xs"></i>
                                            {{ $user->unit->kategori_unit }}
                                        </span>
                                    @else
                                        <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Unit / Fakultas</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-building text-gray-400 text-sm"></i>
                                    @if($user->unit)
                                        {{ $user->unit->nama_unit }}
                                    @else
                                        <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        {{-- ===== DATA KONSELOR ===== --}}
                        @if($user->role === 'konselor')
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-xs font-bold text-green-700 uppercase tracking-wide mb-3 flex items-center gap-1">
                                    <i class="fas fa-user-md"></i> Data Konselor
                                </p>
                            </div>

                            @php $kp = $user->konselorProfile; @endphp

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nomor Lisensi</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-id-badge text-gray-400 text-sm"></i>
                                    @if($kp && $kp->nomor_lisensi) {{ $kp->nomor_lisensi }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Spesialisasi</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-star text-gray-400 text-sm"></i>
                                    @if($kp && $kp->spesialisasi) {{ $kp->spesialisasi }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pengalaman Kerja</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-briefcase text-gray-400 text-sm"></i>
                                    @if($kp && $kp->pengalaman_kerja !== null) {{ $kp->pengalaman_kerja }} Tahun
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pendidikan Terakhir</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-graduation-cap text-gray-400 text-sm"></i>
                                    @if($kp && $kp->pendidikan_terakhir) {{ $kp->pendidikan_terakhir }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Sertifikasi P4GN</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-certificate text-gray-400 text-sm"></i>
                                    @if($kp && $kp->sertifikasi_P4GN)
                                        <span class="text-green-600 font-semibold">✓ Tersertifikasi</span>
                                    @else
                                        <span class="text-gray-500">Belum Tersertifikasi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Instansi</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-building text-gray-400 text-sm"></i>
                                    @if($kp && $kp->instansi) {{ $kp->instansi->nama_instansi }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Jabatan</label>
                                <p class="text-gray-800 font-medium flex items-center gap-2">
                                    <i class="fas fa-user-tie text-gray-400 text-sm"></i>
                                    @if($kp && $kp->jabatan) {{ $kp->jabatan->nama_jabatan }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Bio Singkat</label>
                                <p class="text-gray-800 font-medium">
                                    @if($kp && $kp->bio_singkat) {{ $kp->bio_singkat }}
                                    @else <span class="text-yellow-600 text-sm italic flex items-center gap-1"><i class="fas fa-exclamation-circle text-xs"></i> Belum diisi</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        {{-- Tombol Edit --}}
                        <div class="pt-2">
                            <a href="{{ route('profile.edit') }}"
                               class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection