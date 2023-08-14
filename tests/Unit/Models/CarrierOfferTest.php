<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\CarrierOffer;
use DateTime;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(CarrierOffer::class)]
class CarrierOfferTest extends TestCase
{
    #[Test]
    public function get_deadline_from_expiration()
    {
        $dates = [
            'estimated_date' => fake()->dateTime('next week'),
            'expiration' => fake()->dateTime('next month'),
        ];
        $offer = new CarrierOffer($dates);
        $expectedDeadline = $dates['expiration']->diff(new DateTime())->d;

        $this->assertEquals($expectedDeadline, $offer->deadline);
    }
}
