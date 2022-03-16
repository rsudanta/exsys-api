<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-l text-gray-800 leading-tight">
            {{ __('Kelola Basis Pengetahuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="mb-10 ">
                <a href="{{ route('rules.create') }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Basis Pengetahuan
                </a>
            </div>
            <div class="bg-white" style="overflow-x:auto;">
                <table class=" table-auto w-full">
                    <thead>
                        <tr>
                            <th class="border px-6 py-4">ID</th>
                            <th class="border px-6 py-4">Nama Penyakit</th>
                            <th class="border px-6 py-4">Nama Gejala</th>
                            <th class="border px-6 py-4">MB</th>
                            <th class="border px-6 py-4">MD</th>
                            <th class="border px-6 py-4">Nilai CF</th>
                            <th class="border px-6 py-4">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rules as $item)
                            <tr>
                                <td class="border px-6 py-4 text-center">{{ $item->id }}</td>
                                <td class="border px-6 py-4 text-center">{{ $item->penyakit->nama_penyakit }}</td>
                                <td class="border px-6 py-4 text-center">{{ $item->gejala->nama_gejala }}</td>
                                <td class="border px-6 py-4 text-center">{{ $item->mb }}</td>
                                <td class="border px-6 py-4 text-center">{{ $item->md }}</td>
                                <td class="border px-6 py-4 text-center">{{ $item->nilai_cf }}</td>
                                <td class="border px-6 py-4 text-center">
                                    <a href="{{ route('rules.edit', $item->id) }}"
                                        class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                        Ubah</a>
                                    <form action="{{ route('rules.destroy', $item->id) }}" method="POST"
                                        class="inline-block">
                                        {!! method_field('delete') . csrf_field() !!}
                                        <button type="submit"
                                            class=" inline-block bg-red-500
                                            hover:bg-red-700 text-white font-bold py-2 px-4 mx-2
                                            rounded">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border text-center p-5">Data kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-5">
                {{ $rules->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
