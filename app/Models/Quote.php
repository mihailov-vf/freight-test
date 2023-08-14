<?php

namespace App\Models;

use App\Data\Quote\Dispatcher;
use App\Data\Quote\Recipient;
use FreteRapido\Simulation\Simulation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'external_id',
        'external_request_id',
        'dispatcher',
        'recipient',
    ];

    protected $casts = [
        'external_id' => 'uuid',
        'external_request_id' => 'uuid',
        'dispatcher' => Dispatcher::class,
        'recipient' => Recipient::class,
    ];

    /** @return HasMany<CarrierOffer> */
    public function offers(): HasMany
    {
        $offers = $this->hasMany(CarrierOffer::class);
        $offers->addConstraints();
        return $offers;
    }

    public function addOffersFrom(Simulation $simulation): void
    {
        $offersData = [];
        foreach ($simulation->dispatchers as $quote) {
            if (empty($quote->offers)) {
                continue;
            }

            foreach ($quote->offers as $offer) {
                $offersData[] = [
                    'name' => $offer->carrier->name,
                    'service' => $offer->service,
                    'estimated_date' => $offer->deliveryTime->estimatedDate,
                    'expiration' => $offer->expiration,
                    'price' => $offer->finalPrice,
                ];
            }
        }
        $this->offers()->createMany($offersData);
    }
}
