<x-app-layout>
    <div class="max-w-5xl mx-auto bg-white p-8 mt-10 rounded-xl shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Kiri: Foto & Detail User -->
            <div class="flex flex-col items-center text-center">
                <img src="{{ $user->foto ? asset('storage/'.$user->foto) : asset('assets/default-avatar.png') }}"
                     class="w-32 h-32 rounded-full object-cover mb-4" alt="Foto Profil">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="w-full">
                    @csrf

                    <label class="block text-sm font-semibold mb-1">Ganti Foto</label>
                    <input type="file" name="foto" class="border rounded w-full p-2 text-sm mb-4">

                    <div class="space-y-2 text-left">
                        <div>
                            <label class="font-semibold text-sm">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="border rounded w-full p-2 text-sm">
                        </div>
                        <div>
                            <label class="font-semibold text-sm">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="border rounded w-full p-2 text-sm">
                        </div>
                    </div>

                    <button type="submit" class="mt-4 bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Kanan: Info Keamanan -->
            <div class="md:col-span-2">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-blue-700 mb-2">Keamanan Akun</h3>
                    <ul class="text-sm text-gray-700 list-disc ml-5 space-y-1">
                        <li>Gunakan password yang kuat</li>
                        <li>Jangan bagikan informasi akun</li>
                        <li>Selalu logout setelah selesai</li>
                    </ul>
                </div>
                <a href="{{ route('password.request') }}" class="inline-block bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500">
                    Ganti Password
                </a>

                <div class="mt-8">
                    <h3 class="font-semibold mb-2">Riwayat Konseling</h3>
                    <table class="w-full border text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2">Tanggal Konsultasi</th>
                                <th class="border px-3 py-2">Konselor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-3 py-2 text-center text-gray-500" colspan="2">Belum ada data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
