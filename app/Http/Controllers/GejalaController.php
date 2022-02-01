<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gejala = Gejala::orderBy('id', 'desc')->paginate(20);
        return view('gejala.index', [
            'gejala' => $gejala
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gejala.create');
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
            'nama_gejala' => 'required|unique:gejala',
        ], [
            'nama_gejala.required' => 'Kamu harus mengisi nama penyakit',
            'nama_gejala.unique' => 'Gejala sudah pernah ditambahkan',
        ]);
        Gejala::create([
            'nama_gejala' => $request->nama_gejala,
        ]);

        return redirect()->route('gejala.index');
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
    public function edit(Gejala $gejala)
    {
        return view('gejala.edit', [
            'item' => $gejala
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gejala $gejala)
    {
        if ($request->nama_gejala == $gejala->nama_gejala) {
            $request->validate([
                'nama_gejala' => 'required',
            ], [
                'nama_gejala.required' => 'Kamu harus mengisi nama penyakit',
            ]);
        } else {
            $request->validate([
                'nama_gejala' => 'required|unique:gejala',
            ], [
                'nama_gejala.required' => 'Kamu harus mengisi nama penyakit',
                'nama_gejala.unique' => 'Penyakit sudah ada di dalam daftar',
            ]);
        }
        $data = $request->all();
        $gejala->update($data);

        return redirect()->route('gejala.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gejala $gejala)
    {
        $gejala->delete();
        BasisPengetahuan::where('id_gejala', $gejala->id)->delete();
        return redirect()->route('gejala.index');
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $gejala = Gejala::where('nama_gejala', 'like', "%" . $keyword . "%")->paginate(5);
        return view('gejala.index', compact('gejala'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
