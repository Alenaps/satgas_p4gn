@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="ep-wrap">

    {{-- ===== HEADER ===== --}}
    <div class="ep-header">
        <div class="ep-header-left">
            <a href="{{ route('admin.kelola_pengguna.index') }}" class="ep-back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <div>
                <h1 class="ep-title">Edit Pengguna</h1>
                <p class="ep-subtitle">Perbarui data akun <strong>{{ $user->nama }}</strong></p>
            </div>
        </div>
        {{-- Avatar identitas user yang sedang diedit --}}
        <div class="ep-identity-card">
            <div class="ep-id-avatar {{ $user->role === 'konselor' ? 'ep-id-konselor' : 'ep-id-konsuli' }}">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div>
                <div class="ep-id-name">{{ $user->nama }}</div>
                <div class="ep-id-meta">{{ $user->email }}</div>
                <span class="ep-role-badge {{ $user->role === 'konselor' ? 'ep-role-konselor' : 'ep-role-konsuli' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>

    {{-- ===== ALERT ERROR ===== --}}
    @if($errors->any())
    <div class="ep-alert ep-alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
            <ul class="ep-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.kelola_pengguna.update', $user) }}" id="ep-form">
        @csrf
        @method('PUT')

        <div class="ep-grid">

            {{-- ============================================================ --}}
            {{-- KOLOM KIRI                                                    --}}
            {{-- ============================================================ --}}
            <div class="ep-col-left">

                {{-- --- INFORMASI DASAR --- --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Dasar
                    </div>

                    <div class="ep-field-group">
                        <div class="ep-field ep-field-full">
                            <label class="ep-label" for="nama">
                                Nama Lengkap <span class="ep-required">*</span>
                            </label>
                            <input type="text" id="nama" name="nama"
                                   value="{{ old('nama', $user->nama) }}"
                                   placeholder="Masukkan nama lengkap"
                                   class="ep-input @error('nama') ep-input-error @enderror">
                            @error('nama')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field ep-field-full">
                            <label class="ep-label" for="email">
                                Email <span class="ep-required">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="contoh@email.com"
                                   class="ep-input @error('email') ep-input-error @enderror">
                            @error('email')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="npm_nip">
                                NPM / NIP <span class="ep-required">*</span>
                            </label>
                            <input type="text" id="npm_nip" name="npm_nip"
                                   value="{{ old('npm_nip', $user->npm_nip) }}"
                                   placeholder="Nomor mahasiswa / pegawai"
                                   class="ep-input @error('npm_nip') ep-input-error @enderror">
                            @error('npm_nip')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="no_telp">No. Telepon</label>
                            <input type="text" id="no_telp" name="no_telp"
                                   value="{{ old('no_telp', $user->no_telp) }}"
                                   placeholder="08xx-xxxx-xxxx"
                                   class="ep-input @error('no_telp') ep-input-error @enderror">
                            @error('no_telp')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label">
                                Jenis Kelamin <span class="ep-required">*</span>
                            </label>
                            <div class="ep-radio-group">
                                <label class="ep-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki"
                                           {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Laki-laki' ? 'checked' : '' }}>
                                    <span class="ep-radio-text">Laki-laki</span>
                                </label>
                                <label class="ep-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan"
                                           {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Perempuan' ? 'checked' : '' }}>
                                    <span class="ep-radio-text">Perempuan</span>
                                </label>
                            </div>
                            @error('jenis_kelamin')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="role">
                                Role <span class="ep-required">*</span>
                            </label>
                            <select id="role" name="role"
                                    class="ep-select @error('role') ep-input-error @enderror"
                                    onchange="toggleKonselorSection(this.value)">
                                <option value="">-- Pilih Role --</option>
                                <option value="konselor" {{ old('role', $user->role) === 'konselor' ? 'selected' : '' }}>Konselor</option>
                                <option value="konsuli"  {{ old('role', $user->role) === 'konsuli'  ? 'selected' : '' }}>Konsuli</option>
                            </select>
                            @error('role')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="status_sivitas_id">
                                Status Sivitas <span class="ep-required">*</span>
                            </label>
                            <select id="status_sivitas_id" name="status_sivitas_id"
                                    class="ep-select @error('status_sivitas_id') ep-input-error @enderror">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statusSivitasList as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status_sivitas_id', $user->status_sivitas_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->nama }}
                                    </option>
                                @endforeach
                            </select>
                           @error('status_sivitas_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kategori Unit --}}
<div class="ep-field">
    <label class="ep-label" for="kategori_unit">Kategori Unit</label>
    <select id="kategori_unit" name="kategori_unit" class="ep-select">
        <option value="">-- Pilih Kategori --</option>
        <option value="Akademik"     {{ old('kategori_unit', $user->unit?->kategori_unit) == 'Akademik'     ? 'selected' : '' }}>Akademik</option>
        <option value="Administrasi" {{ old('kategori_unit', $user->unit?->kategori_unit) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
    </select>
</div>

                    {{-- Nama Unit (difilter berdasarkan kategori) --}}
                    <div class="ep-field">
                        <label class="ep-label" for="unit_id">
                            Unit <span class="ep-required">*</span>
                        </label>
                        <select id="unit_id" name="unit_id"
                                class="ep-select @error('unit_id') ep-input-error @enderror">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}"
                                    data-kategori="{{ $unit->kategori_unit }}"
                                    {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')<p class="ep-field-error">{{ $message }}</p>@enderror
                    </div>
                    </div>
                </div>

                {{-- --- GANTI PASSWORD --- --}}
                <div class="ep-card">
                    <div class="ep-card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        Ganti Password
                        <span class="ep-card-badge">Opsional</span>
                    </div>
                    <p class="ep-card-hint">Kosongkan jika tidak ingin mengubah password.</p>

                    <div class="ep-field-group">
                        <div class="ep-field">
                            <label class="ep-label" for="password">Password Baru</label>
                            <div class="ep-input-wrap">
                                <input type="password" id="password" name="password"
                                       placeholder="Min. 8 karakter"
                                       class="ep-input ep-input-pass @error('password') ep-input-error @enderror">
                                <button type="button" class="ep-eye-btn" onclick="togglePass('password')">
                                    <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')<p class="ep-field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="ep-field">
                            <label class="ep-label" for="password_confirmation">Konfirmasi Password</label>
                            <div class="ep-input-wrap">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi password baru"
                                       class="ep-input ep-input-pass">
                                <button type="button" class="ep-eye-btn" onclick="togglePass('password_confirmation')">
                                    <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end kolom kiri --}}

            {{-- ============================================================ --}}
            {{-- KOLOM KANAN                                                   --}}
            {{-- ============================================================ --}}
            <div class="ep-col-right">

                {{-- --- PROFIL KONSELOR (tampil hanya jika role = konselor) --- --}}
                <div id="konselor-section"
                     class="{{ old('role', $user->role) === 'konselor' ? '' : 'ep-hidden' }}">
                    <div class="ep-card">
                        <div class="ep-card-header ep-card-header-konselor">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Profil Konselor
                        </div>

                        <div class="ep-field-group">
                            <div class="ep-field">
                                <label class="ep-label" for="nomor_lisensi">
                                    Nomor Lisensi <span class="ep-required">*</span>
                                </label>
                                <input type="text" id="nomor_lisensi" name="nomor_lisensi"
                                       value="{{ old('nomor_lisensi', $user->konselorProfile->nomor_lisensi ?? '') }}"
                                       placeholder="Contoh: LIS-2024-001"
                                       class="ep-input @error('nomor_lisensi') ep-input-error @enderror">
                                @error('nomor_lisensi')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field">
                                <label class="ep-label" for="spesialisasi">
                                    Spesialisasi <span class="ep-required">*</span>
                                </label>
                                <input type="text" id="spesialisasi" name="spesialisasi"
                                       value="{{ old('spesialisasi', $user->konselorProfile->spesialisasi ?? '') }}"
                                       placeholder="Contoh: Konseling Napza, Trauma"
                                       class="ep-input @error('spesialisasi') ep-input-error @enderror">
                                @error('spesialisasi')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field">
                                <label class="ep-label" for="pendidikan_terakhir">
                                    Pendidikan Terakhir <span class="ep-required">*</span>
                                </label>
                                <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir"
                                       value="{{ old('pendidikan_terakhir', $user->konselorProfile->pendidikan_terakhir ?? '') }}"
                                       placeholder="Contoh: S2 Psikologi"
                                       class="ep-input @error('pendidikan_terakhir') ep-input-error @enderror">
                                @error('pendidikan_terakhir')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field">
                                <label class="ep-label" for="id_instansi">
                                    Instansi <span class="ep-required">*</span>
                                </label>
                                <select id="id_instansi" name="id_instansi"
                                        class="ep-select @error('id_instansi') ep-input-error @enderror">
                                    <option value="">-- Pilih Instansi --</option>
                                    @foreach($instansiList as $instansi)
                                        <option value="{{ $instansi->id }}"
                                            {{ old('id_instansi', $user->konselorProfile->id_instansi ?? '') == $instansi->id ? 'selected' : '' }}>
                                            {{ $instansi->nama_instansi }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_instansi')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field">
                                <label class="ep-label" for="id_jabatan">
                                    Jabatan <span class="ep-required">*</span>
                                </label>
                                <select id="id_jabatan" name="id_jabatan"
                                        class="ep-select @error('id_jabatan') ep-input-error @enderror">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($jabatanList as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            {{ old('id_jabatan', $user->konselorProfile->id_jabatan ?? '') == $jabatan->id ? 'selected' : '' }}>
                                            {{ $jabatan->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_jabatan')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                          <div class="ep-field @error('pengalaman_kerja') ep-input-error @enderror">
                                <label class="ep-label" for="pengalaman_kerja">
                                    Pengalaman Kerja (Tahun) <span class="ep-required">*</span>
                                </label>
                                <input type="number" id="pengalaman_kerja" name="pengalaman_kerja"
                                    min="0" max="50"
                                    value="{{ old('pengalaman_kerja', $user->konselorProfile->pengalaman_kerja ?? '') }}"
                                    placeholder="Contoh: 5"
                                    class="ep-input @error('pengalaman_kerja') ep-input-error @enderror">
                                @error('pengalaman_kerja')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field ep-field-full">
                                <label class="ep-label" for="bio_singkat">Bio Singkat</label>
                                <textarea id="bio_singkat" name="bio_singkat" rows="3"
                                          placeholder="Deskripsi singkat konselor (opsional)…"
                                          class="ep-textarea @error('bio_singkat') ep-input-error @enderror">{{ old('bio_singkat', $user->konselorProfile->bio_singkat ?? '') }}</textarea>
                                @error('bio_singkat')<p class="ep-field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="ep-field ep-field-full">
                                <label class="ep-toggle-wrap">
                                    <input type="hidden" name="sertifikasi_P4GN" value="0">
                                    <input type="checkbox" id="sertifikasi_P4GN" name="sertifikasi_P4GN" value="1"
                                           {{ old('sertifikasi_P4GN', $user->konselorProfile->sertifikasi_P4GN ?? false) ? 'checked' : '' }}>
                                    <span class="ep-toggle-text">
                                        <span class="ep-toggle-title">Sertifikasi P4GN</span>
                                        <span class="ep-toggle-desc">Centang jika konselor telah memiliki sertifikasi P4GN</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>{{-- end konselor-section --}}

                {{-- --- PLACEHOLDER JIKA KONSULI --- --}}
                <div id="konsuli-placeholder"
                     class="{{ old('role', $user->role) === 'konselor' ? 'ep-hidden' : '' }}">
                    <div class="ep-card ep-card-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="ep-empty-title">Role Konsuli</p>
                        <p class="ep-empty-desc">Tidak ada data profil tambahan untuk role konsuli.</p>
                    </div>
                </div>

                {{-- --- INFO AKUN --- --}}
                <div class="ep-card ep-card-meta">
                    <div class="ep-card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Info Akun
                    </div>
                    <div class="ep-meta-list">
                        <div class="ep-meta-row">
                            <span class="ep-meta-label">ID Pengguna</span>
                            <span class="ep-meta-value ep-meta-mono">{{ $user->id }}</span>
                        </div>
                        <div class="ep-meta-row">
                            <span class="ep-meta-label">Bergabung</span>
                            <span class="ep-meta-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="ep-meta-row">
                            <span class="ep-meta-label">Terakhir diperbarui</span>
                            <span class="ep-meta-value">{{ $user->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="ep-meta-row">
                            <span class="ep-meta-label">Status Email</span>
                            <span class="ep-tag-ok">Terdaftar</span>
                        </div>
                    </div>
                </div>

            </div>{{-- end kolom kanan --}}

        </div>{{-- end grid --}}

        {{-- ===== ACTION BAR ===== --}}
        <div class="ep-action-bar">
            <a href="{{ route('admin.kelola_pengguna.index') }}" class="ep-btn-cancel">
                Batal
            </a>
            <button type="submit" class="ep-btn-save">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

{{-- ===== STYLES ===== --}}
<style>
:root {
    --ep-primary: #2563eb;
    --ep-primary-light: #eff6ff;
    --ep-konselor: #0d9488;
    --ep-konselor-light: #f0fdfa;
    --ep-konsuli: #7c3aed;
    --ep-konsuli-light: #f5f3ff;
    --ep-danger: #dc2626;
    --ep-danger-light: #fef2f2;
    --ep-gray-50: #f9fafb;
    --ep-gray-100: #f3f4f6;
    --ep-gray-200: #e5e7eb;
    --ep-gray-300: #d1d5db;
    --ep-gray-400: #9ca3af;
    --ep-gray-500: #6b7280;
    --ep-gray-600: #4b5563;
    --ep-gray-800: #1f2937;
    --ep-radius: 12px;
    --ep-shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.06);
}

/* ---- wrap ---- */
.ep-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }

/* ---- header ---- */
.ep-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem;
}
.ep-header-left { display: flex; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
.ep-back-btn {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--ep-gray-600); text-decoration: none; font-size: .875rem;
    padding: .45rem .85rem; border: 1px solid var(--ep-gray-200); border-radius: 8px;
    background: #fff; transition: background .15s; white-space: nowrap; margin-top: .15rem;
}
.ep-back-btn:hover { background: var(--ep-gray-50); }
.ep-title { font-size: 1.5rem; font-weight: 700; color: var(--ep-gray-800); margin: 0; }
.ep-subtitle { color: var(--ep-gray-400); font-size: .875rem; margin: .2rem 0 0; }

/* identity card */
.ep-identity-card {
    display: flex; align-items: center; gap: .85rem;
    background: #fff; border: 1px solid var(--ep-gray-200);
    border-radius: var(--ep-radius); padding: .75rem 1.1rem;
    box-shadow: var(--ep-shadow);
}
.ep-id-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1.1rem; flex-shrink: 0;
}
.ep-id-konselor { background: var(--ep-konselor-light); color: var(--ep-konselor); }
.ep-id-konsuli  { background: var(--ep-konsuli-light);  color: var(--ep-konsuli); }
.ep-id-name { font-weight: 600; color: var(--ep-gray-800); font-size: .9rem; }
.ep-id-meta { font-size: .78rem; color: var(--ep-gray-400); margin-bottom: .3rem; }

/* ---- alert ---- */
.ep-alert {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .9rem 1.1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: .875rem;
}
.ep-alert-error { background: var(--ep-danger-light); color: #991b1b; border: 1px solid #fecaca; }
.ep-error-list { margin: .35rem 0 0 1.1rem; padding: 0; }
.ep-error-list li { margin-bottom: .2rem; }

/* ---- layout grid ---- */
.ep-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media(max-width: 820px) {
    .ep-grid { grid-template-columns: 1fr; }
}
.ep-col-left, .ep-col-right { display: flex; flex-direction: column; gap: 1.25rem; }

/* ---- card ---- */
.ep-card {
    background: #fff; border: 1px solid var(--ep-gray-200);
    border-radius: var(--ep-radius); box-shadow: var(--ep-shadow); overflow: hidden;
}
.ep-card-header {
    display: flex; align-items: center; gap: .6rem;
    padding: .9rem 1.25rem;
    border-bottom: 1px solid var(--ep-gray-100);
    font-weight: 600; color: var(--ep-gray-800); font-size: .9rem;
    background: var(--ep-gray-50);
}
.ep-card-header-konselor { color: var(--ep-konselor); }
.ep-card-badge {
    margin-left: auto; font-size: .72rem; font-weight: 500;
    background: var(--ep-gray-100); color: var(--ep-gray-500);
    padding: .2rem .6rem; border-radius: 20px;
}
.ep-card-hint {
    padding: .65rem 1.25rem 0;
    font-size: .8rem; color: var(--ep-gray-400); margin: 0;
}
.ep-card-empty {
    padding: 2.5rem 1.25rem; text-align: center;
    color: var(--ep-gray-400); display: flex; flex-direction: column;
    align-items: center; gap: .5rem;
}
.ep-card-empty svg { opacity: .35; }
.ep-empty-title { font-weight: 600; font-size: .95rem; margin: 0; }
.ep-empty-desc { font-size: .82rem; margin: 0; }

/* meta card */
.ep-card-meta { }
.ep-meta-list { padding: .5rem 0; }
.ep-meta-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .6rem 1.25rem; font-size: .83rem;
    border-bottom: 1px solid var(--ep-gray-100);
}
.ep-meta-row:last-child { border-bottom: none; }
.ep-meta-label { color: var(--ep-gray-400); }
.ep-meta-value { color: var(--ep-gray-800); font-weight: 500; }
.ep-meta-mono { font-family: monospace; font-size: .8rem; }
.ep-tag-ok {
    font-size: .75rem; font-weight: 600;
    background: #f0fdf4; color: #166534;
    padding: .15rem .6rem; border-radius: 20px;
}

/* ---- field group ---- */
.ep-field-group {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 1rem; padding: 1.25rem;
}
.ep-field { display: flex; flex-direction: column; gap: .4rem; }
.ep-field-full { grid-column: 1 / -1; }

.ep-label {
    font-size: .8rem; font-weight: 600; color: var(--ep-gray-600);
    text-transform: uppercase; letter-spacing: .04em;
}
.ep-required { color: var(--ep-danger); }

/* inputs */
.ep-input, .ep-select, .ep-textarea {
    padding: .6rem .85rem;
    border: 1px solid var(--ep-gray-200); border-radius: 8px;
    font-size: .875rem; outline: none; width: 100%;
    color: var(--ep-gray-800); background: #fff;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.ep-input:focus, .ep-select:focus, .ep-textarea:focus {
    border-color: var(--ep-primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.ep-input-error { border-color: var(--ep-danger) !important; }
.ep-textarea { resize: vertical; min-height: 90px; }
.ep-field-error { font-size: .78rem; color: var(--ep-danger); margin: 0; }

/* password input wrapper */
.ep-input-wrap { position: relative; }
.ep-input-pass { padding-right: 2.5rem; }
.ep-eye-btn {
    position: absolute; right: .65rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--ep-gray-400); padding: .2rem;
    display: flex; align-items: center;
}
.ep-eye-btn:hover { color: var(--ep-gray-600); }

/* radio */
.ep-radio-group { display: flex; gap: 1rem; }
.ep-radio-label { display: flex; align-items: center; gap: .45rem; cursor: pointer; }
.ep-radio-text { font-size: .875rem; color: var(--ep-gray-700); }

/* toggle (checkbox) */
.ep-toggle-wrap {
    display: flex; align-items: flex-start; gap: .75rem;
    cursor: pointer; padding: .75rem 1rem;
    background: var(--ep-gray-50); border-radius: 8px;
    border: 1px solid var(--ep-gray-200);
}
.ep-toggle-wrap input[type="checkbox"] { margin-top: .15rem; width: 16px; height: 16px; cursor: pointer; }
.ep-toggle-text { display: flex; flex-direction: column; gap: .15rem; }
.ep-toggle-title { font-size: .875rem; font-weight: 600; color: var(--ep-gray-800); }
.ep-toggle-desc { font-size: .78rem; color: var(--ep-gray-400); }

/* role badge (reuse dari index) */
.ep-role-badge { padding: .2rem .65rem; border-radius: 20px; font-size: .72rem; font-weight: 600; display: inline-block; }
.ep-role-konselor { background: var(--ep-konselor-light); color: var(--ep-konselor); }
.ep-role-konsuli  { background: var(--ep-konsuli-light);  color: var(--ep-konsuli); }

/* ---- action bar ---- */
.ep-action-bar {
    display: flex; justify-content: flex-end; align-items: center; gap: .75rem;
    margin-top: 1.5rem; padding: 1.1rem 1.25rem;
    background: #fff; border: 1px solid var(--ep-gray-200);
    border-radius: var(--ep-radius); box-shadow: var(--ep-shadow);
}
.ep-btn-cancel {
    padding: .6rem 1.25rem; border-radius: 8px;
    border: 1px solid var(--ep-gray-200); background: #fff;
    color: var(--ep-gray-600); text-decoration: none; font-size: .875rem; font-weight: 600;
    transition: background .15s;
}
.ep-btn-cancel:hover { background: var(--ep-gray-50); }
.ep-btn-save {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .6rem 1.4rem; border-radius: 8px;
    background: var(--ep-primary); color: #fff;
    border: none; cursor: pointer; font-size: .875rem; font-weight: 600;
    transition: background .15s;
}
.ep-btn-save:hover { background: #1d4ed8; }

/* ---- utilities ---- */
.ep-hidden { display: none !important; }

@media(max-width: 640px) {
    .ep-field-group { grid-template-columns: 1fr; }
    .ep-header { flex-direction: column; }
    .ep-identity-card { width: 100%; }
}
</style>

{{-- ... form dan style ... --}}

    <script>
    // ---- Filter Unit berdasarkan Kategori ----
    (function () {
        const kategoriSelect = document.getElementById('kategori_unit');
        const unitSelect     = document.getElementById('unit_id');
        const allOptions     = Array.from(unitSelect.options);

        function filterUnit() {
            const kategori = kategoriSelect.value;
            const current  = unitSelect.value;

            unitSelect.innerHTML = '<option value="">-- Pilih Unit --</option>';

            allOptions.forEach(opt => {
                if (opt.value === '') return;
                if (!kategori || opt.dataset.kategori === kategori) {
                    unitSelect.appendChild(opt.cloneNode(true));
                }
            });

            if (current) unitSelect.value = current;
        }

        kategoriSelect.addEventListener('change', filterUnit);

        document.addEventListener('DOMContentLoaded', function () {
            if (kategoriSelect.value) filterUnit();
        });
    })();

    // ---- Toggle Konselor Section ----
    function toggleKonselorSection(role) {
        // ... kode yang sudah ada ...
    }

    function togglePass(fieldId) {
        // ... kode yang sudah ada ...
    }
    </script>

<script>
function toggleKonselorSection(role) {
    const konselor  = document.getElementById('konselor-section');
    const konsuli   = document.getElementById('konsuli-placeholder');

    if (role === 'konselor') {
        konselor.classList.remove('ep-hidden');
        konsuli.classList.add('ep-hidden');
    } else {
        konselor.classList.add('ep-hidden');
        konsuli.classList.remove('ep-hidden');
    }
}

function togglePass(fieldId) {
    const input = document.getElementById(fieldId);
    const eye   = document.getElementById('eye-' + fieldId);
    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        input.type = 'password';
        eye.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
</script>

@endsection