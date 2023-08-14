<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarrierOffer extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $primaryKey = null;

    protected $fillable = [
        'name',
        'service',
        'estimated_date',
        'expiration',
        'price',
    ];

    protected $casts = [
        'name' => 'string',
        'service' => 'string',
        'estimated_date' => 'datetime',
        'expiration' => 'datetime',
        'price' => 'float',
    ];

    /** @var string[] */
    protected $appends = [
        'deadline'
    ];

    /** @return BelongsTo<Quote,CarrierOffer> */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function getDeadlineAttribute(): int
    {
        return $this->expiration->diff(new \DateTime())->d;
    }
}
