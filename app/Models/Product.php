<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
    public function transaksi() {
        return $this->belongsTo(Transaksi::class);
    }

    public function product_ratings() {
        return $this->hasMany(ProductRating::class)->where('status',1);
    }
}
