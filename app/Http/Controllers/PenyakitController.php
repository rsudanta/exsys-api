<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuan;
use App\Models\Hasil;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penyakit = Penyakit::orderBy('id', 'desc')->paginate(20);
        return view('penyakit.index', [
            'penyakit' => $penyakit
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('penyakit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_penyakit' => 'required|unique:penyakit',
            'solusi' => 'required',
        ], [
            'nama_penyakit.required' => 'Kamu harus mengisi nama penyakit',
            'nama_penyakit.unique' => 'Penyakit sudah pernah ditambahkan',
            'solusi.required' => 'Kamu harus mengisi solusi',
        ]);
        Penyakit::create([
            'nama_penyakit' => $request->nama_penyakit,
            'solusi' => $request->solusi,
        ]);

        return redirect()->route('penyakit.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Penyakit $penyakit)
    {
        return view('penyakit.edit', [
            'item' => $penyakit
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penyakit $penyakit)
    {
        if ($request->nama_penyakit == $penyakit->nama_penyakit) {
            $request->validate([
                'nama_penyakit' => 'required',
                'solusi' => 'required',
            ], [
                'nama_penyakit.required' => 'Kamu harus mengisi nama penyakit',
                'solusi.required' => 'Kamu harus mengisi solusi',
            ]);
        } else {
            $request->validate([
                'nama_penyakit' => 'required|unique:penyakit',
                'solusi' => 'required',
            ], [
                'nama_penyakit.required' => 'Kamu harus mengisi nama penyakit',
                'nama_penyakit.unique' => 'Penyakit sudah ada di dalam daftar',
                'solusi.required' => 'Kamu harus mengisi solusi',
            ]);
        }
        $data = $request->all();
        $penyakit->update($data);

        return redirect()->route('penyakit.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penyakit $penyakit)
    {
        $penyakit->delete();
        BasisPengetahuan::where('id_penyakit', $penyakit->id)->delete();
        Hasil::where('id_penyakit', $penyakit->id)->delete();
        return redirect()->route('penyakit.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $penyakit = Penyakit::where('nama_penyakit', 'like', "%" . $keyword . "%")->paginate(5);
        return view('penyakit.index', compact('penyakit'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
