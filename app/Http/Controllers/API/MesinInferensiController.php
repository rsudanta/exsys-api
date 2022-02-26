<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Hasil;
use App\Models\Penyakit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MesinInferensiController extends Controller
{
    public function hitungCF(Request $request)
    {
        $basisPengetahuan = BasisPengetahuan::get();
        if (count($basisPengetahuan) == 0) {
            return ResponseFormatter::error([
                'message' => 'Rules data is empty',
            ], 'Rules data is empty');
        }
        $input = json_decode($request->getContent(), true);
        $gejalaUser = array();

        for ($x = 0; $x < count($input); $x++) {
            array_push($gejalaUser, $input[$x]['id_gejala']);
        }

        $id_penyakit = BasisPengetahuan::distinct()->pluck('id_penyakit');

        for ($x = 0; $x < count($id_penyakit); $x++) {
            $penyakit[] = BasisPengetahuan::where('id_penyakit', $id_penyakit[$x])->get();
        }

        for ($x = 0; $x < count($penyakit); $x++) {
            for ($z = 0; $z < count($penyakit[$x]); $z++) {
                if (in_array($penyakit[$x][$z]['id_gejala'], $gejalaUser)) {
                    for ($y = 0; $y < count($input); $y++) {
                        if ($penyakit[$x][$z]['id_gejala'] == $input[$y]['id_gejala']) {
                            $penyakit[$x][$z]['nilai_cf'] *= $input[$y]['nilai_cf'];
                        }
                    }
                } else {
                    $penyakit[$x][$z]['nilai_cf'] *= 0;
                }
            }
        }
        $hasilCombineTemp = array();
        $hasilCF = array();

        for ($x = 0; $x < count($penyakit); $x++) {
            for ($z = 0; $z < count($penyakit[$x]); $z++) {

                if ($z == 0 && isset($penyakit[$x][$z + 1]) == false) {
                    $hasilCombine = $penyakit[$x][$z]['nilai_cf'];
                    array_push($hasilCombineTemp, $hasilCombine);
                } else if ($z == 0 && isset($penyakit[$x][$z + 1]) == true) {
                    $hasilCombine = $penyakit[$x][$z]['nilai_cf'] + ($penyakit[$x][$z + 1]['nilai_cf'] * (1 - $penyakit[$x][$z]['nilai_cf']));
                    array_push($hasilCombineTemp, $hasilCombine);
                } else if (isset($penyakit[$x][$z + 1]) == true) {
                    $hasilCombine = $hasilCombineTemp[0] + ($penyakit[$x][$z + 1]['nilai_cf'] * (1 - $hasilCombineTemp[0]));
                    $hasilCombineTemp = [];
                    array_push($hasilCombineTemp, $hasilCombine);
                }
            }
            array_push($hasilCF, $hasilCombineTemp);
            $hasilCombineTemp = [];
        }

        for ($x = 0; $x < count($id_penyakit); $x++) {
            $hasilDiagnosa[] = Penyakit::where('id', $id_penyakit[$x])->get();
            $hasilDiagnosa[$x][0]['hasilCF'] = $hasilCF[$x][0];
        }

        // return $hasilDiagnosa;

        $hasilMax = array();

        for ($x = 0; $x < count($hasilDiagnosa); $x++) {
            if ($x == 0) {
                array_push($hasilMax, $hasilDiagnosa[$x][0]);
            } else if ($hasilMax[0]['hasilCF'] < $hasilDiagnosa[$x][0]['hasilCF']) {
                $hasilMax = [];
                array_push($hasilMax, $hasilDiagnosa[$x][0]);
            }
        }
        if ($hasilMax[0]['hasilCF'] == 0) {
            $hasilMax = ["Hasil nilai keyakinan sama dengan nol"];
            Hasil::create([
                'hasil_cf' => null,
                'id_penyakit' => null,
                'id_user' => Auth::id(),
            ]);
            return ResponseFormatter::success($hasilMax[0], 'Consultation Success');
        } else {
            Hasil::create([
                'hasil_cf' => $hasilMax[0]['hasilCF'],
                'id_penyakit' => $hasilMax[0]['id'],
                'id_user' => Auth::id(),
            ]);
            return ResponseFormatter::success($hasilMax[0], 'Consultation Success');
        }
    }

    public function getGejala()
    {
        $gejala = Gejala::all();

        if ($gejala) {
            return ResponseFormatter::success(
                $gejala,
                'Successfully get data'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data not found',
                404
            );
        }
    }
}
