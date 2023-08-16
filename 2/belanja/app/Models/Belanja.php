<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belanja extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vouchers()
    {
        return $this->belongsTo(Voucher::class, 'kode_belanja');
    }
}
