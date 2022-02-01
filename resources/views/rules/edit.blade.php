<x-app-layout>
    <x-slot name="header">
        <div class="text-l text-gray-800 leading-tight">
            <a href="{{ route('rules.index') }}"> Kelola Basis Pengetahuan</a> >
            <span class="font-bold"> Ubah Basis Pengetahuan
                CF({{ $item->gejala->nama_gejala }},{{ $item->penyakit->nama_penyakit }})</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                @if ($errors->any())
                    <div class="mb-5" role="alert">
                        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                            Terjadi Kesalahan !
                        </div>
                        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                            <p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </p>
                        </div>
                    </div>
                @endif
                <form class="w-full" action="{{ route('rules.update', $item->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-last-name">
                                Penyakit
                            </label>
                            <select name="id_penyakit"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name">
                                @foreach ($penyakit as $itemPenyakit)
                                    <option value="{{ $itemPenyakit->id }}"
                                        {{ $item->id_penyakit == $itemPenyakit->id ? 'selected' : '' }}>
                                        {{ $itemPenyakit->nama_penyakit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-last-name">
                                Gejala
                            </label>
                            <select name="id_gejala"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name">
                                @foreach ($gejala as $itemGejala)
                                    <option value="{{ $itemGejala->id }}"
                                        {{ $item->id_gejala == $itemGejala->id ? 'selected' : '' }}>
                                        {{ $itemGejala->nama_gejala }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                for="grid-last-name">
                                Nilai CF
                            </label>
                            <select name="nilai_cf"
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                id="grid-last-name">
                                <option value="0" {{ $item->nilai_cf == 0 ? 'selected' : '' }}>Tidak (0)
                                </option>
                                <option value="0.4" {{ $item->nilai_cf == 0.4 ? 'selected' : '' }}>Mungkin (0.4)
                                </option>
                                <option value="0.6" {{ $item->nilai_cf == 0.6 ? 'selected' : '' }}>Kemungkinan Besar
                                    (0.6)</option>
                                <option value="0.8" {{ $item->nilai_cf == 0.8 ? 'selected' : '' }}>Hampir Pasti (0.8)
                                </option>
                                <option value="1" {{ $item->nilai_cf == 1 ? 'selected' : '' }}>Pasti (1)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3 text-right">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
