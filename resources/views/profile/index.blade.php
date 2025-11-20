<x-app-layout>
    <div class="max-w-5xl mx-auto bg-white p-8 mt-10 rounded-xl shadow">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Foto dan Detail Kiri -->
            <div class="text-center flex flex-col items-center">
                <img src="{{ $user->foto ? asset('storage/'.$user->foto) : asset('assets/default-avatar.png') }}"
                     class="w-32 h-32 rounded-full object-cover mb-4">

                <p class="font-semibold text-lg">{{ $user->nama }}</p>

                <div class="mt-6 w-full text-left">
                    <p class="font-semibold text-sm">NPM/NIP:</p>
                    <p class="bg-gray-100 p-2 rounded">{{ $user->npm_nip }}</p>

                    <p class="font-semibold text-sm mt-3">Email:</p>
                    <p class="bg-gray-100 p-2 rounded">{{ $user->email }}</p>

                    <p class="font-semibold text-sm mt-3">No. Telepon:</p>
                    <p class="bg-gray-100 p-2 rounded">{{ $user->no_telp ?? '-' }}</p>
                </div>

                <a href="{{ route('profile.edit') }}"
                   class="mt-4 text-blue-600 text-sm underline">
                    Edit Profile
                </a>
            </div>

            <!-- Kanan -->
            <div class="md:col-span-2">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h3 class="font-semibold text-blue-700 mb-2">Keamanan Akun</h3>
                    <ul class="list-disc ml-5 text-sm text-gray-700 space-y-1">
                        <li>Gunakan password yang kuat</li>
                        <li>Jangan bagikan informasi akun</li>
                        <li>Selalu logout setelah selesai</li>
                    </ul>
                </div>

                <a href="{{ route('password.request') }}"
                   class="inline-block bg-yellow-400 px-4 py-2 rounded hover:bg-yellow-500">
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
                                <td colspan="2"
                                    class="border px-3 py-2 text-center text-gray-500">
                                    Belum ada data
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
