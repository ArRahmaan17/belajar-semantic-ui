<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_code', 'branch_code', 'branch_id', 'recipient', 'incoming_at', 'outcoming_at', 'description', 'message', 'photo', 'ip_address'];
}
