@extends('layouts.auth')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-green-500 py-8">
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-8 border border-green-100">

            <h2 class="text-2xl font-bold text-blue-700 text-center mb-6">
                DAFTAR
            </h2>

            <form method="POST" action="{{ route('register') }}" class="relative z-50 flex flex-col gap-4" novalidate>
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required autofocus
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <option value="">Pilih</option>
                        <option value="Laki-laki"  {{ old('jenis_kelamin') == 'Laki-laki'  ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan"  {{ old('jenis_kelamin') == 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NPM / NIP --}}
                <div>
                    <label for="npm_nip" class="block text-sm font-semibold text-gray-700">NPM / NIP</label>
                    <input id="npm_nip" type="text" name="npm_nip" value="{{ old('npm_nip') }}" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('npm_nip')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. Telepon --}}
                <div>
                    <label for="no_telp" class="block text-sm font-semibold text-gray-700">No. Telepon</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}"
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('no_telp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Sivitas --}}
                <div>
                    <label for="status_sivitas_id" class="block text-sm font-semibold text-gray-700">Status Sivitas</label>
                    <select id="status_sivitas_id" name="status_sivitas_id" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <option value="">-- Pilih Status --</option>
                        @foreach ($statusSivitasList as $status)
                            <option value="{{ $status->id }}" {{ old('status_sivitas_id') == $status->id ? 'selected' : '' }}>
                                {{ $status->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_sivitas_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori Unit --}}
                <div>
                    <label for="kategori_unit" class="block text-sm font-semibold text-gray-700">Kategori Unit</label>
                    <select id="kategori_unit" name="kategori_unit"
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Akademik"     {{ old('kategori_unit') == 'Akademik'     ? 'selected' : '' }}>Akademik</option>
                        <option value="Administrasi" {{ old('kategori_unit') == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                    </select>
                </div>

                {{-- Nama Unit (difilter berdasarkan kategori via JavaScript) --}}
                <div>
                    <label for="unit_id" class="block text-sm font-semibold text-gray-700">Nama Unit</label>
                    <select id="unit_id" name="unit_id" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <option value="">-- Pilih Nama Unit --</option>
                        @foreach ($units as $unit)
                            <option
                                value="{{ $unit->id }}"
                                data-kategori="{{ $unit->kategori_unit }}"
                                {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password dengan real-time strength checker --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="mt-1 block w-full rounded-md px-2 py-1 pr-10 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        {{-- Toggle show/hide --}}
                        <button type="button" id="togglePassword"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                            tabindex="-1">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                       -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Strength bar --}}
                    <div class="mt-2 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                        <div id="strengthBar" class="h-full rounded-full transition-all duration-300 w-0"></div>
                    </div>
                    <p id="strengthLabel" class="text-xs mt-0.5 font-medium text-gray-400"></p>

                    {{-- Syarat password dengan live check --}}
                    <ul class="mt-2 text-xs space-y-0.5" id="passwordRules">
                        <li id="rule-length"   class="flex items-center gap-1 text-gray-400">
                            <span class="rule-icon">○</span> Minimal 8 karakter
                        </li>
                        <li id="rule-upper"    class="flex items-center gap-1 text-gray-400">
                            <span class="rule-icon">○</span> Huruf besar (A-Z)
                        </li>
                        <li id="rule-lower"    class="flex items-center gap-1 text-gray-400">
                            <span class="rule-icon">○</span> Huruf kecil (a-z)
                        </li>
                        <li id="rule-number"   class="flex items-center gap-1 text-gray-400">
                            <span class="rule-icon">○</span> Angka (0-9)
                        </li>
                        <li id="rule-symbol"   class="flex items-center gap-1 text-gray-400">
                            <span class="rule-icon">○</span> Simbol (!@#$%^&* dll)
                        </li>
                    </ul>

                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="mt-1 block w-full rounded-md px-2 py-1 pr-10 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <button type="button" id="toggleConfirm"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                            tabindex="-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                                       -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="matchMsg" class="text-xs mt-1 hidden"></p>
                </div>

                {{-- Persetujuan --}}
                <div class="flex items-start mt-2">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-emerald-500 text-emerald-600 cursor-pointer"
                            {{ old('terms') ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-600 cursor-pointer text-justify">
                            Saya menyetujui ketersediaan data untuk dipergunakan oleh konsulen sebagai kepentingan
                            statistik, dan data yang tersimpan dijamin kerahasiaannya oleh konsulen.
                        </label>
                        @error('terms')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        Daftar
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login di sini</a>
            </p>

        </div>
    </div>

    <script>
    // ── 1. Filter Nama Unit berdasarkan Kategori ─────────────────────────────
    const kategoriSelect = document.getElementById('kategori_unit');
    const unitSelect     = document.getElementById('unit_id');
    const allUnitOptions = Array.from(unitSelect.options);

    function filterUnits(selectedKategori, preserveValue) {
        unitSelect.innerHTML = '<option value="">-- Pilih Nama Unit --</option>';
        allUnitOptions.forEach(option => {
            if (option.value === '') return;
            if (!selectedKategori || option.dataset.kategori === selectedKategori) {
                unitSelect.appendChild(option.cloneNode(true));
            }
        });
        if (preserveValue) unitSelect.value = preserveValue;
    }

    kategoriSelect.addEventListener('change', function () {
        filterUnits(this.value, null);
    });

    // Restore old values setelah validasi gagal
    window.addEventListener('DOMContentLoaded', () => {
        const oldKategori = "{{ old('kategori_unit') }}";
        const oldUnitId   = "{{ old('unit_id') }}";
        if (oldKategori) {
            kategoriSelect.value = oldKategori;
            filterUnits(oldKategori, oldUnitId);
        }
    });

    // ── 2. Toggle show/hide password ────────────────────────────────────────
    function setupToggle(btnId, inputId) {
        document.getElementById(btnId).addEventListener('click', () => {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    }
    setupToggle('togglePassword', 'password');
    setupToggle('toggleConfirm',  'password_confirmation');

    // ── 3. Real-time password strength checker ───────────────────────────────
    const passwordInput = document.getElementById('password');
    const strengthBar   = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');

    const rules = {
        'rule-length': val => val.length >= 8,
        'rule-upper':  val => /[A-Z]/.test(val),
        'rule-lower':  val => /[a-z]/.test(val),
        'rule-number': val => /[0-9]/.test(val),
        'rule-symbol': val => /[^A-Za-z0-9]/.test(val),
    };

    const strengthConfig = [
        { label: '',          color: '',                  width: 'w-0'    },
        { label: 'Sangat Lemah', color: 'bg-red-500',    width: '20%'    },
        { label: 'Lemah',     color: 'bg-orange-500',    width: '40%'    },
        { label: 'Cukup',     color: 'bg-yellow-400',    width: '60%'    },
        { label: 'Kuat',      color: 'bg-blue-500',      width: '80%'    },
        { label: 'Sangat Kuat', color: 'bg-emerald-500', width: '100%'   },
    ];

    passwordInput.addEventListener('input', function () {
        const val   = this.value;
        let passed  = 0;

        Object.entries(rules).forEach(([id, test]) => {
            const li   = document.getElementById(id);
            const icon = li.querySelector('.rule-icon');
            if (test(val)) {
                li.classList.remove('text-gray-400');
                li.classList.add('text-emerald-600');
                icon.textContent = '✓';
                passed++;
            } else {
                li.classList.remove('text-emerald-600');
                li.classList.add('text-gray-400');
                icon.textContent = '○';
            }
        });

        const cfg = val.length === 0 ? strengthConfig[0] : strengthConfig[passed];
        strengthBar.style.width = cfg.width;
        strengthBar.className   = `h-full rounded-full transition-all duration-300 ${cfg.color}`;
        strengthLabel.textContent = cfg.label;
        strengthLabel.className   = `text-xs mt-0.5 font-medium ${
            passed <= 1 ? 'text-red-500'
            : passed === 2 ? 'text-orange-500'
            : passed === 3 ? 'text-yellow-500'
            : passed === 4 ? 'text-blue-500'
            : passed === 5 ? 'text-emerald-600'
            : 'text-gray-400'
        }`;

        checkMatch();
    });

    // ── 4. Cek kecocokan password & konfirmasi ───────────────────────────────
    const confirmInput = document.getElementById('password_confirmation');
    const matchMsg     = document.getElementById('matchMsg');

    function checkMatch() {
        if (!confirmInput.value) { matchMsg.classList.add('hidden'); return; }
        matchMsg.classList.remove('hidden');
        if (passwordInput.value === confirmInput.value) {
            matchMsg.textContent = '✓ Password cocok';
            matchMsg.className   = 'text-xs mt-1 text-emerald-600 font-medium';
        } else {
            matchMsg.textContent = '✗ Password tidak cocok';
            matchMsg.className   = 'text-xs mt-1 text-red-500 font-medium';
        }
    }

    confirmInput.addEventListener('input', checkMatch);
    </script>
@endsection