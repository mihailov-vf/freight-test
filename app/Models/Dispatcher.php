<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatcher extends Model
{
    use HasFactory;
    use HasUuids;

    /** @var string[] */
    protected $fillable = [
        'address_zipcode',
        'registered_number'
    ];
}
