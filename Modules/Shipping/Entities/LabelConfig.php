<?php

namespace Modules\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabelConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
        'status',
        'created_by',
    ];


}
