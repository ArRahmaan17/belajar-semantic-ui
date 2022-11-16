<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_code', 'branch_code', 'branch_id', 'recipient', 'incoming_at', 'outcoming_at', 'description', 'message', 'photo', 'ip_address'];

    static function getAllCheckpointManifest(String $branchCode, Int $limit = 20, Int $lastId = 0)
    {
        $dataFiltered = Checkpoint::join('manifest', 'manifest.kodemanifest', '=', 'checkpoints.transaction_code')->where('checkpoints.branch_code', '=', $branchCode)->where('checkpoints.id', '>=', $lastId)->limit($limit)->get();
        $allData = Checkpoint::where('branch_code', '=', $branchCode)->count();
        return [
            'dataFiltered' => $dataFiltered,
            'countAll' => $allData,
        ];
    }
}
