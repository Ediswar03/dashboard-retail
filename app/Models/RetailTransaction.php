<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'invoice_no', 'stock_code', 'description', 'quantity',
        'invoice_date', 'unit_price', 'customer_id', 'country',
        'total_price', 'year', 'month', 'month_name', 'day_of_week', 'hour',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];
}
