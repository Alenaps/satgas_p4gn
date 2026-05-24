@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="kp-wrap">

    {{-- ===== HEADER ===== --}}
    <div class="kp-header">
        <div>
            <h1 class="kp-title">Kelola Pengguna</h1>
            <p class="kp-subtitle">Manajemen akun konselor &amp; konsuli sistem</p>
        </div>
        <a href="{{ route('admin.kelola_pengguna.create') }}" class="btn-primary-kp">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- ===== ALERT ===== --}}
    @if(session('success'))
    <div class="kp-alert kp-alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== FILTER & SEARCH ===== --}}
    <div class="kp-filter-card">
        <form method="GET" action="{{ route('admin.kelola_pengguna.index') }}" class="kp-filter-form">
            <div class="kp-search-wrap">
                <svg class="kp-search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="search" placeholder="Cari nama, email, NPM/NIP…"
                       value="{{ request('search') }}" class="kp-search-input">
            </div>
            <select name="role" class="kp-select">
                <option value="">Semua Role</option>
                <option value="konselor" {{ request('role') === 'konselor' ? 'selected' : '' }}>Konselor</option>
                <option value="konsuli"  {{ request('role') === 'konsuli'  ? 'selected' : '' }}>Konsuli</option>
            </select>
            <button type="submit" class="btn-filter-kp">Filter</button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.kelola_pengguna.index') }}" class="btn-reset-kp">Reset</a>
            @endif
        </form>
    </div>

    {{-- ===== STATS ===== --}}
    <div class="kp-stats-row">
        <div class="kp-stat-card kp-stat-total">
            <span class="kp-stat-num">{{ $users->total() }}</span>
            <span class="kp-stat-label">Total Pengguna</span>
        </div>
        <div class="kp-stat-card kp-stat-konselor">
            <span class="kp-stat-num">{{ \App\Models\User::where('role','konselor')->count() }}</span>
            <span class="kp-stat-label">Konselor</span>
        </div>
        <div class="kp-stat-card kp-stat-konsuli">
            <span class="kp-stat-num">{{ \App\Models\User::where('role','konsuli')->count() }}</span>
            <span class="kp-stat-label">Konsuli</span>
        </div>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="kp-table-card">
        @if($users->count())
        <div class="kp-table-scroll">
            <table class="kp-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>NPM / NIP</th>
                       <th>Status Sivitas</th>
                        <th>Unit</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $i => $user)
                <tr>
                    <td class="kp-td-no">{{ $users->firstItem() + $i }}</td>
                    <td>
                        <div class="kp-user-cell">
                            <div class="kp-avatar {{ $user->role === 'konselor' ? 'kp-avatar-konselor' : 'kp-avatar-konsuli' }}">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="kp-user-name">{{ $user->nama }}</div>
                                <div class="kp-user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="kp-td-npm">{{ $user->npm_nip ?? '-' }}</td>
                    <td>
                    <span class="kp-tag-sivitas">{{ $user->statusSivitas->nama ?? '-' }}</span>
                    </td>
                    <td class="kp-unit-name">
                        {{ $user->unit->nama_unit ?? '-' }}
                    </td>
                    <td>
                        <span class="kp-role-badge {{ $user->role === 'konselor' ? 'kp-role-konselor' : 'kp-role-konsuli' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <div class="kp-action-group">
                            <a href="{{ route('admin.kelola_pengguna.edit', $user) }}"
                               class="kp-btn-edit" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.kelola_pengguna.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus pengguna {{ addslashes($user->nama) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="kp-btn-delete" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="kp-pagination">
            <div class="kp-pag-info">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
            </div>
            {{ $users->links('pagination::bootstrap-4') }}
        </div>

        @else
        <div class="kp-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p>Belum ada pengguna ditemukan.</p>
            <a href="{{ route('admin.kelola_pengguna.create') }}" class="btn-primary-kp" style="margin-top:1rem">
                Tambah Pengguna Pertama
            </a>
        </div>
        @endif
    </div>

</div>

{{-- ===== STYLES ===== --}}
<style>
:root{
    --kp-primary:#2563eb;
    --kp-primary-light:#eff6ff;
    --kp-konselor:#0d9488;
    --kp-konselor-light:#f0fdfa;
    --kp-konsuli:#7c3aed;
    --kp-konsuli-light:#f5f3ff;
    --kp-danger:#dc2626;
    --kp-danger-light:#fef2f2;
    --kp-gray-50:#f9fafb;
    --kp-gray-100:#f3f4f6;
    --kp-gray-200:#e5e7eb;
    --kp-gray-400:#9ca3af;
    --kp-gray-600:#4b5563;
    --kp-gray-800:#1f2937;
    --kp-radius:12px;
    --kp-shadow:0 1px 3px rgba(0,0,0,.08),0 4px 12px rgba(0,0,0,.06);
}
.kp-wrap{max-width:1200px;margin:0 auto;padding:1.5rem;}
/* header */
.kp-header{display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;}
.kp-title{font-size:1.6rem;font-weight:700;color:var(--kp-gray-800);margin:0;}
.kp-subtitle{color:var(--kp-gray-400);font-size:.875rem;margin:.25rem 0 0;}
/* buttons */
.btn-primary-kp{display:inline-flex;align-items:center;gap:.5rem;background:var(--kp-primary);color:#fff;padding:.6rem 1.2rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:.875rem;transition:background .2s;}
.btn-primary-kp:hover{background:#1d4ed8;}
.btn-filter-kp{background:var(--kp-primary);color:#fff;border:none;padding:.55rem 1.1rem;border-radius:8px;cursor:pointer;font-size:.875rem;font-weight:600;}
.btn-reset-kp{color:var(--kp-gray-600);text-decoration:none;font-size:.875rem;padding:.55rem .8rem;border-radius:8px;border:1px solid var(--kp-gray-200);background:#fff;}
/* alert */
.kp-alert{display:flex;align-items:center;gap:.6rem;padding:.85rem 1.1rem;border-radius:8px;margin-bottom:1.25rem;font-size:.875rem;}
.kp-alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
/* filter */
.kp-filter-card{background:#fff;border:1px solid var(--kp-gray-200);border-radius:var(--kp-radius);padding:1rem 1.25rem;margin-bottom:1.25rem;box-shadow:var(--kp-shadow);}
.kp-filter-form{display:flex;gap:.75rem;flex-wrap:wrap;align-items:center;}
.kp-search-wrap{position:relative;flex:1;min-width:220px;}
.kp-search-icon{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--kp-gray-400);}
.kp-search-input{width:100%;padding:.55rem .75rem .55rem 2.25rem;border:1px solid var(--kp-gray-200);border-radius:8px;font-size:.875rem;outline:none;}
.kp-search-input:focus{border-color:var(--kp-primary);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.kp-select{padding:.55rem .85rem;border:1px solid var(--kp-gray-200);border-radius:8px;font-size:.875rem;outline:none;background:#fff;}
/* stats */
.kp-stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem;}
.kp-stat-card{background:#fff;border:1px solid var(--kp-gray-200);border-radius:var(--kp-radius);padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.25rem;box-shadow:var(--kp-shadow);}
.kp-stat-num{font-size:1.75rem;font-weight:700;line-height:1;}
.kp-stat-label{font-size:.8rem;color:var(--kp-gray-400);}
.kp-stat-total .kp-stat-num{color:var(--kp-primary);}
.kp-stat-konselor .kp-stat-num{color:var(--kp-konselor);}
.kp-stat-konsuli .kp-stat-num{color:var(--kp-konsuli);}
/* table card */
.kp-table-card{background:#fff;border:1px solid var(--kp-gray-200);border-radius:var(--kp-radius);box-shadow:var(--kp-shadow);overflow:hidden;}
.kp-table-scroll{overflow-x:auto;}
.kp-table{width:100%;border-collapse:collapse;font-size:.875rem;}
.kp-table thead tr{background:var(--kp-gray-50);border-bottom:1px solid var(--kp-gray-200);}
.kp-table th{padding:.85rem 1rem;text-align:left;font-weight:600;color:var(--kp-gray-600);white-space:nowrap;font-size:.8rem;text-transform:uppercase;letter-spacing:.05em;}
.kp-table td{padding:.85rem 1rem;border-bottom:1px solid var(--kp-gray-100);vertical-align:middle;}
.kp-table tr:last-child td{border-bottom:none;}
.kp-table tr:hover td{background:var(--kp-gray-50);}
.kp-td-no{color:var(--kp-gray-400);font-size:.8rem;width:40px;}
.kp-td-npm{color:var(--kp-gray-600);font-family:monospace;font-size:.8rem;}
/* user cell */
.kp-user-cell{display:flex;align-items:center;gap:.75rem;}
.kp-avatar{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.95rem;flex-shrink:0;}
.kp-avatar-konselor{background:var(--kp-konselor-light);color:var(--kp-konselor);}
.kp-avatar-konsuli{background:var(--kp-konsuli-light);color:var(--kp-konsuli);}
.kp-user-name{font-weight:600;color:var(--kp-gray-800);}
.kp-user-email{font-size:.78rem;color:var(--kp-gray-400);}
/* status unit */
.kp-td-status{display:flex;flex-direction:column;gap:.25rem;}
.kp-tag-sivitas{font-size:.75rem;background:var(--kp-primary-light);color:var(--kp-primary);padding:.15rem .55rem;border-radius:20px;display:inline-block;width:fit-content;font-weight:500;}
.kp-unit-name{font-size:.78rem;color:var(--kp-gray-600);}
/* role badge */
.kp-role-badge{padding:.25rem .75rem;border-radius:20px;font-size:.75rem;font-weight:600;}
.kp-role-konselor{background:var(--kp-konselor-light);color:var(--kp-konselor);}
.kp-role-konsuli{background:var(--kp-konsuli-light);color:var(--kp-konsuli);}
/* action */
.kp-action-group{display:flex;gap:.5rem;align-items:center;}
.kp-btn-edit,.kp-btn-delete{display:inline-flex;align-items:center;gap:.35rem;padding:.4rem .8rem;border-radius:6px;font-size:.78rem;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:background .15s;}
.kp-btn-edit{background:var(--kp-primary-light);color:var(--kp-primary);}
.kp-btn-edit:hover{background:#dbeafe;}
.kp-btn-delete{background:var(--kp-danger-light);color:var(--kp-danger);}
.kp-btn-delete:hover{background:#fee2e2;}
/* pagination */
.kp-pagination{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.25rem;border-top:1px solid var(--kp-gray-100);flex-wrap:wrap;gap:.5rem;}
.kp-pag-info{font-size:.8rem;color:var(--kp-gray-400);}
.kp-pagination .pagination{margin:0;}
/* empty */
.kp-empty{padding:4rem 2rem;text-align:center;color:var(--kp-gray-400);}
.kp-empty svg{margin:0 auto 1rem;display:block;opacity:.4;}
.kp-empty p{font-size:.95rem;}
@media(max-width:640px){
    .kp-stats-row{grid-template-columns:repeat(2,1fr);}
    .kp-header{flex-direction:column;}
}
</style>
@endsection