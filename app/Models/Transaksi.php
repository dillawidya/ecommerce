<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'total_harga', 'nama_resep'
    ];

    public function items() {
        return $this->hasMany(TransaksiItem::class, 'id_transaksi');
    }
    
    
}
