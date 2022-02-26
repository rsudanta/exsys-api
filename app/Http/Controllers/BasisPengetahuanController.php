<?php

namespace App\Http\Controllers;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BasisPengetahuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = BasisPengetahuan::orderBy('id', 'asc')->paginate(20);
        return view('rules.index', [
            'rules' => $rules
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $penyakit = Penyakit::get();
        $gejala = Gejala::get();
        return view('rules.create', [
            'penyakit' => $penyakit,
            'gejala' => $gejala,
        ]);
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
            'id_gejala' => [
                Rule::unique('basis_pengetahuan')
                    ->where('id_penyakit', $request->id_penyakit)
            ]
        ], [
            'id_gejala.unique' => 'Kombinasi antara penyakit dan gejala ini sudah pernah ditambahkan',
        ]);

        BasisPengetahuan::create([
            'nilai_cf' => $request->nilai_cf,
            'id_penyakit' => $request->id_penyakit,
            'id_gejala' => $request->id_gejala,
        ]);

        return redirect()->route('rules.index');
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
    public function edit($id)
    {
        $rules = BasisPengetahuan::find($id);
        $penyakit = Penyakit::get();
        $gejala = Gejala::get();
        return view('rules.edit', [
            'item' => $rules,
            'penyakit' => $penyakit,
            'gejala' => $gejala,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = BasisPengetahuan::find($id);
        if ($request->id_gejala != $rules->id_gejala || $request->id_penyakit != $rules->id_penyakit) {
            $request->validate([
                'id_gejala' => [
                    Rule::unique('basis_pengetahuan')
                        ->where('id_penyakit', $request->id_penyakit)
                ]
            ], [
                'id_gejala.unique' => 'Kombinasi antara penyakit dan gejala ini sudah pernah ditambahkan',
            ]);
        }

        $data = $request->all();
        $rules->update($data);

        return redirect()->route('rules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = BasisPengetahuan::find($id);
        $item->delete();
        return redirect()->route('rules.index');
    }
}
