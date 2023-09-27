<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'transaction_details';
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
