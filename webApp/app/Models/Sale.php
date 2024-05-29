<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['counterparty_id'];

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function products()
{
    return $this->belongsToMany(Product::class, 'sales_product')
        ->withPivot('quantity', 'unit_price', 'amount')
        ->withTimestamps();
}

}
