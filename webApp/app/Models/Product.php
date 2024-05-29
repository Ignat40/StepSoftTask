<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'photo', 'counterparty_id'];

    public function counterparty()
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sales_product')
            ->withPivot('quantity', 'unit_price', 'amount')
            ->withTimestamps();
    }
}
