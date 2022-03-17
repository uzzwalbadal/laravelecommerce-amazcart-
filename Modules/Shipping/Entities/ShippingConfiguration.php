<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

}
