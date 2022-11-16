<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    protected $table = 'tracking';
    protected $fillable = ['idtransaksi', 'notransaksi', 'kdcabang', 'idcabang', 'tanggal', 'jam', 'keterangan', 'user', 'tampil', 'foto', 'posisi', 'masuk', 'selesai', 'penerima', 'idmanifest', 'kodemanifest', 'ipaddres', 'pc', 'manifest'];

    static function createTrackingHistory(array $trackingHistory)
    {
        if (!Tracking::insert($trackingHistory)) {
            return false;
        }
        return true;
    }
}
