<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Manifest extends Model
{
    use HasFactory;
    protected $table = 'manifest';

    static function getManifest(String $manifestCode, String $vendorCode)
    {
        $dataManifest = Manifest::join('m_cabang', 'm_cabang.kodecabang', '=', 'manifest.kodeagen')->where('manifest.kodemanifest', '=', $manifestCode)->where('manifest.kodeagen', '=', $vendorCode)->select('manifest.*', 'm_cabang.namacabang as nama_cabang')->first();
        $dataDetailManifest = Manifest::detailManifest($manifestCode);
        if (empty(json_decode(json_encode($dataManifest)))) {
            return ['message' => 'Data manifest not found', 'status' => false, 'data' => null];
        }
        return ['message' => 'Data manifest is found', 'status' => true, 'data' => json_encode($dataManifest), 'details' => json_encode($dataDetailManifest)];
    }
    static function detailManifest(String $manifestCode)
    {
        $dataDetailManifest = DB::table('manifest_detail')->where('kodemanifest', '=', $manifestCode)->get();
        if (json_encode($dataDetailManifest) == "[]") {
            return [];
        }
        return $dataDetailManifest;
    }
}
