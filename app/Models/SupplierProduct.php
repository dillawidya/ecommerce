<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    use HasFactory;

    protected $fillable = ['nama_produk', 'harga', 'qty_awal', 'qty_beli', 'qty_total', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTotalPenjualan()
    {
        $this->total_penjualan = $this->qty_beli * $this->harga;
        $this->save();
    }

}
