@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="kpf-wrap">

    {{-- ===== BREADCRUMB ===== --}}
    <div class="kpf-breadcrumb">
        <a href="{{ route('admin.kelola_pengguna.index') }}">Kelola Pengguna</a>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span>Tambah Pengguna</span>
    </div>

    <div class="kpf-layout">

        {{-- ===== SIDEBAR INFO ===== --}}
        <aside class="kpf-sidebar">
            <div class="kpf-info-card">
                <div class="kpf-info-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h3>Tambah Pengguna Baru</h3>
                <p>Isi data dengan lengkap dan benar. Bidang bertanda <span class="req-star">*</span> wajib diisi.</p>
            </div>

            <div class="kpf-role-guide">
                <div class="kpf-role-item kpf-role-konselor">
                    <strong>Konselor</strong>
                    <p>Tenaga profesional yang memberikan layanan konseling. Wajib mengisi data profil tambahan.</p>
                </div>
                <div class="kpf-role-item kpf-role-konsuli">
                    <strong>Konsuli</strong>
                    <p>Civitas akademika yang menerima layanan konseling (mahasiswa/karyawan).</p>
                </div>
            </div>
        </aside>

        {{-- ===== FORM UTAMA ===== --}}
        <div class="kpf-main">
           <form method="POST" action="{{ route('admin.kelola_pengguna.store') }}" id="formTambah" autocomplete="off">
                @csrf

                {{-- ── SEKSI 1: AKUN ── --}}
                <div class="kpf-section">
                    <div class="kpf-section-head">
                        <span class="kpf-section-num">1</span>
                        <div>
                            <h2 class="kpf-section-title">Informasi Akun</h2>
                            <p class="kpf-section-sub">Email &amp; password untuk login</p>
                        </div>
                    </div>
                    <div class="kpf-grid-2">
                        <div class="kpf-field @error('email') has-error @enderror">
                            <label>Email <span class="req-star">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" autocomplete="off">
                            @error('email')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('role') has-error @enderror">
                            <label>Role <span class="req-star">*</span></label>
                            <select name="role" id="roleSelect">
                                <option value="">-- Pilih Role --</option>
                                <option value="konsuli"  {{ old('role') === 'konsuli'  ? 'selected' : '' }}>Konsuli</option>
                                <option value="konselor" {{ old('role') === 'konselor' ? 'selected' : '' }}>Konselor</option>
                            </select>
                            @error('role')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('password') has-error @enderror">
                            <label>Password <span class="req-star">*</span></label>
                            <div class="kpf-pwd-wrap">
                               <input type="password" name="password" id="pwd" placeholder="Min. 8 karakter" autocomplete="new-password">
                                <button type="button" class="kpf-toggle-pwd" data-target="pwd">👁</button>
                            </div>
                            @error('password')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('password_confirmation') has-error @enderror">
                            <label>Konfirmasi Password <span class="req-star">*</span></label>
                            <div class="kpf-pwd-wrap">
                               <input type="password" name="password_confirmation" id="pwd2" placeholder="Ulangi password" autocomplete="new-password">
                                <button type="button" class="kpf-toggle-pwd" data-target="pwd2">👁</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SEKSI 2: DATA DIRI ── --}}
                <div class="kpf-section">
                    <div class="kpf-section-head">
                        <span class="kpf-section-num">2</span>
                        <div>
                            <h2 class="kpf-section-title">Data Diri</h2>
                            <p class="kpf-section-sub">Identitas pengguna</p>
                        </div>
                    </div>
                    <div class="kpf-grid-2">
                        <div class="kpf-field kpf-full @error('nama') has-error @enderror">
                            <label>Nama Lengkap <span class="req-star">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                   placeholder="Nama sesuai identitas">
                            @error('nama')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('jenis_kelamin') has-error @enderror">
                            <label>Jenis Kelamin <span class="req-star">*</span></label>
                            <select name="jenis_kelamin">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki"  {{ old('jenis_kelamin') === 'Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"  {{ old('jenis_kelamin') === 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('npm_nip') has-error @enderror">
                            <label>NPM / NIP <span class="req-star">*</span></label>
                            <input type="text" name="npm_nip" value="{{ old('npm_nip') }}"
                                   placeholder="Nomor mahasiswa / pegawai">
                            @error('npm_nip')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('no_telp') has-error @enderror">
                            <label>No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                                   placeholder="08xx-xxxx-xxxx">
                            @error('no_telp')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>

                        <div class="kpf-field @error('status_sivitas_id') has-error @enderror">
                            <label>Status Sivitas <span class="req-star">*</span></label>
                            <select name="status_sivitas_id">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statusSivitasList as $ss)
                                <option value="{{ $ss->id }}" {{ old('status_sivitas_id') == $ss->id ? 'selected' : '' }}>
                                    {{ $ss->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('status_sivitas_id')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>

                        {{-- Kategori Unit --}}
                        <div class="kpf-field">
                            <label>Kategori Unit</label>
                            <select id="kategori_unit_create" name="kategori_unit">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Akademik"     {{ old('kategori_unit') == 'Akademik'     ? 'selected' : '' }}>Akademik</option>
                                <option value="Administrasi" {{ old('kategori_unit') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                            </select>
                        </div>

                        {{-- Nama Unit --}}
                        <div class="kpf-field @error('unit_id') has-error @enderror">
                            <label>Unit / Prodi <span class="req-star">*</span></label>
                            <select id="unit_id_create" name="unit_id">
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}"
                                    data-kategori="{{ $unit->kategori_unit }}"
                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                                @endforeach
                            </select>
                            @error('unit_id')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- ── SEKSI 3: PROFIL KONSELOR (conditional) ── --}}
                <div class="kpf-section kpf-section-konselor" id="sectionKonselor"
                     style="{{ old('role') === 'konselor' ? '' : 'display:none' }}">
                    <div class="kpf-section-head">
                        <span class="kpf-section-num kpf-num-konselor">3</span>
                        <div>
                            <h2 class="kpf-section-title">Profil Konselor</h2>
                            <p class="kpf-section-sub">Data profesional konselor — muncul otomatis saat role Konselor dipilih</p>
                        </div>
                    </div>
                    <div class="kpf-grid-2">
                        <div class="kpf-field @error('nomor_lisensi') has-error @enderror">
                            <label>Nomor Lisensi <span class="req-star">*</span></label>
                            <input type="text" name="nomor_lisensi" value="{{ old('nomor_lisensi') }}"
                                   placeholder="No. lisensi konselor">
                            @error('nomor_lisensi')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('spesialisasi') has-error @enderror">
                            <label>Spesialisasi <span class="req-star">*</span></label>
                            <input type="text" name="spesialisasi" value="{{ old('spesialisasi') }}"
                                   placeholder="Mis. Konseling Akademik">
                            @error('spesialisasi')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('pendidikan_terakhir') has-error @enderror">
                            <label>Pendidikan Terakhir <span class="req-star">*</span></label>
                            <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir') }}"
                                   placeholder="Mis. S2 Psikologi">
                            @error('pendidikan_terakhir')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('id_instansi') has-error @enderror">
                            <label>Instansi <span class="req-star">*</span></label>
                            <select name="id_instansi">
                                <option value="">-- Pilih Instansi --</option>
                                @foreach($instansiList as $inst)
                                <option value="{{ $inst->id }}" {{ old('id_instansi') == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->nama_instansi }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_instansi')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('id_jabatan') has-error @enderror">
                            <label>Jabatan <span class="req-star">*</span></label>
                            <select name="id_jabatan">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatanList as $jab)
                                <option value="{{ $jab->id }}" {{ old('id_jabatan') == $jab->id ? 'selected' : '' }}>
                                    {{ $jab->nama_jabatan }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_jabatan')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field @error('pengalaman_kerja') has-error @enderror">
                            <label>Pengalaman Kerja (Tahun) <span class="req-star">*</span></label>
                            <input type="number" name="pengalaman_kerja"
                                min="0" max="50"
                                value="{{ old('pengalaman_kerja') }}"
                                placeholder="Contoh: 5">
                            @error('pengalaman_kerja')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field kpf-full @error('bio_singkat') has-error @enderror">
                            <label>Bio Singkat</label>
                            <textarea name="bio_singkat" rows="2"
                                      placeholder="Bio singkat konselor (opsional)">{{ old('bio_singkat') }}</textarea>
                            @error('bio_singkat')<span class="kpf-err">{{ $message }}</span>@enderror
                        </div>
                        <div class="kpf-field kpf-full">
                            <label class="kpf-check-wrap">
                                <input type="checkbox" name="sertifikasi_P4GN" value="1"
                                       {{ old('sertifikasi_P4GN') ? 'checked' : '' }}>
                                <span>Memiliki Sertifikasi P4GN</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ── FOOTER FORM ── --}}
                <div class="kpf-footer">
                    <a href="{{ route('admin.kelola_pengguna.index') }}" class="kpf-btn-cancel">Batal</a>
                    <button type="submit" class="kpf-btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>{{-- end kpf-main --}}
    </div>{{-- end kpf-layout --}}
</div>

<style>
:root{
    --kpf-primary:#2563eb;--kpf-primary-light:#eff6ff;
    --kpf-konselor:#0d9488;--kpf-konselor-light:#f0fdfa;
    --kpf-err:#dc2626;--kpf-gray-200:#e5e7eb;--kpf-gray-400:#9ca3af;
    --kpf-gray-600:#4b5563;--kpf-gray-800:#1f2937;
    --kpf-radius:12px;--kpf-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 12px rgba(0,0,0,.06);
}
.kpf-wrap{max-width:1100px;margin:0 auto;padding:1.5rem;}
/* breadcrumb */
.kpf-breadcrumb{display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--kpf-gray-400);margin-bottom:1.25rem;}
.kpf-breadcrumb a{color:var(--kpf-primary);text-decoration:none;}
.kpf-breadcrumb span{color:var(--kpf-gray-600);}
/* layout */
.kpf-layout{display:grid;grid-template-columns:260px 1fr;gap:1.5rem;align-items:start;}
/* sidebar */
.kpf-sidebar{display:flex;flex-direction:column;gap:1rem;position:sticky;top:1.5rem;}
.kpf-info-card{background:#fff;border:1px solid var(--kpf-gray-200);border-radius:var(--kpf-radius);padding:1.25rem;box-shadow:var(--kpf-shadow);}
.kpf-info-icon{width:48px;height:48px;background:var(--kpf-primary-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--kpf-primary);margin-bottom:.85rem;}
.kpf-info-card h3{font-size:1rem;font-weight:700;color:var(--kpf-gray-800);margin:0 0 .4rem;}
.kpf-info-card p{font-size:.82rem;color:var(--kpf-gray-400);margin:0;line-height:1.5;}
.req-star{color:var(--kpf-err);}
.kpf-role-guide{display:flex;flex-direction:column;gap:.75rem;}
.kpf-role-item{background:#fff;border:1px solid var(--kpf-gray-200);border-radius:10px;padding:1rem;box-shadow:var(--kpf-shadow);}
.kpf-role-item strong{display:block;font-size:.85rem;margin-bottom:.3rem;}
.kpf-role-item p{font-size:.78rem;color:var(--kpf-gray-400);margin:0;line-height:1.45;}
.kpf-role-konselor{border-left:3px solid var(--kpf-konselor);}
.kpf-role-konselor strong{color:var(--kpf-konselor);}
.kpf-role-konsuli{border-left:3px solid #7c3aed;}
.kpf-role-konsuli strong{color:#7c3aed;}
/* section */
.kpf-section{background:#fff;border:1px solid var(--kpf-gray-200);border-radius:var(--kpf-radius);padding:1.5rem;margin-bottom:1.25rem;box-shadow:var(--kpf-shadow);}
.kpf-section-konselor{border-color:#99f6e4;background:#fafffe;}
.kpf-section-head{display:flex;align-items:flex-start;gap:.85rem;margin-bottom:1.25rem;}
.kpf-section-num{width:32px;height:32px;border-radius:50%;background:var(--kpf-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;}
.kpf-num-konselor{background:var(--kpf-konselor);}
.kpf-section-title{font-size:1rem;font-weight:700;color:var(--kpf-gray-800);margin:0 0 .15rem;}
.kpf-section-sub{font-size:.8rem;color:var(--kpf-gray-400);margin:0;}
/* grid */
.kpf-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;}
.kpf-full{grid-column:1/-1;}
/* field */
.kpf-field{display:flex;flex-direction:column;gap:.4rem;}
.kpf-field label{font-size:.82rem;font-weight:600;color:var(--kpf-gray-600);}
.kpf-field input,.kpf-field select,.kpf-field textarea{padding:.6rem .85rem;border:1px solid var(--kpf-gray-200);border-radius:8px;font-size:.875rem;outline:none;background:#fff;width:100%;box-sizing:border-box;}
.kpf-field input:focus,.kpf-field select:focus,.kpf-field textarea:focus{border-color:var(--kpf-primary);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.kpf-field.has-error input,.kpf-field.has-error select,.kpf-field.has-error textarea{border-color:var(--kpf-err);}
.kpf-err{font-size:.78rem;color:var(--kpf-err);}
/* pwd */
.kpf-pwd-wrap{position:relative;}
.kpf-pwd-wrap input{padding-right:2.5rem;}
.kpf-toggle-pwd{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:.95rem;padding:0;}
/* checkbox */
.kpf-check-wrap{display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.85rem;color:var(--kpf-gray-600);}
.kpf-check-wrap input{width:auto;accent-color:var(--kpf-konselor);}
/* footer */
.kpf-footer{display:flex;justify-content:flex-end;gap:.75rem;padding-top:.5rem;}
.kpf-btn-cancel{padding:.65rem 1.25rem;border:1px solid var(--kpf-gray-200);border-radius:8px;color:var(--kpf-gray-600);text-decoration:none;font-size:.875rem;font-weight:600;background:#fff;}
.kpf-btn-submit{display:flex;align-items:center;gap:.5rem;padding:.65rem 1.4rem;background:var(--kpf-primary);color:#fff;border:none;border-radius:8px;font-size:.875rem;font-weight:600;cursor:pointer;}
.kpf-btn-submit:hover{background:#1d4ed8;}
@media(max-width:800px){
    .kpf-layout{grid-template-columns:1fr;}
    .kpf-sidebar{position:static;}
    .kpf-grid-2{grid-template-columns:1fr;}
}
</style>

<script>
// Toggle tampilan seksi konselor
const roleSelect  = document.getElementById('roleSelect');
const secKonselor = document.getElementById('sectionKonselor');
roleSelect.addEventListener('change', () => {
    secKonselor.style.display = roleSelect.value === 'konselor' ? '' : 'none';
});

// Toggle show/hide password
document.querySelectorAll('.kpf-toggle-pwd').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.target);
        input.type  = input.type === 'password' ? 'text' : 'password';
        btn.textContent = input.type === 'password' ? '👁' : '🙈';
    });
});
</script>

<script>
// ---- Filter Unit berdasarkan Kategori ----
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSel = document.getElementById('kategori_unit_create');
    const unitSel     = document.getElementById('unit_id_create');
    const allOpts     = Array.from(unitSel.querySelectorAll('option'));

    function filterUnit() {
        const kategori = kategoriSel.value;
        const current  = unitSel.value;

        // Kosongkan semua option kecuali placeholder
        unitSel.innerHTML = '<option value="">-- Pilih Unit --</option>';

        allOpts.forEach(opt => {
            if (opt.value === '') return;
            // Tampilkan semua kalau kategori belum dipilih, atau filter sesuai kategori
            if (!kategori || opt.getAttribute('data-kategori') === kategori) {
                unitSel.appendChild(opt.cloneNode(true));
            }
        });

        // Pertahankan nilai yang dipilih sebelumnya jika masih tersedia
        unitSel.value = current;
    }

    kategoriSel.addEventListener('change', filterUnit);

    // Jalankan filter saat load jika ada old value
    if (kategoriSel.value) {
        filterUnit();
        const oldUnit = "{{ old('unit_id') }}";
        if (oldUnit) unitSel.value = oldUnit;
    }
});
</script>

@endsection