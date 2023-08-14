<?php

declare(strict_types=1);

namespace App\Models;

use App\Data\Metrics\QuotesMetrics;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MetricsReader
{
    public function readMetricsFromLastQuotes(int $quotesNumber): QuotesMetrics
    {
        $quotesQuery = DB::table('quotes', 'q')->select(['q.id' => 'id'])->orderByDesc('q.id');

        if ($quotesNumber > 0) {
            $quotesQuery->limit($quotesNumber);
        }

        $quotes = $quotesQuery->get();
        $carriers = Db::table('carrier_offers', 'c')
            ->select([
                'c.name' => 'name',
                'c.price' => 'price'
            ])
            ->whereIn('quote_id', $quotes->unique('id')->pluck('id')->all())
            ->groupBy(['c.name', 'c.price'])->orderBy('name')->orderBy('quote_id')->get();

        return QuotesMetrics::from([
            'lower_price' => round((float)$carriers->min('price'), 2),
            'higher_price' => round((float)$carriers->max('price'), 2),
            'carriers_metrics' => $carriers->groupBy('name')
                ->map(function (Collection $group) {
                    return [
                        'name' => $group->first()->name,
                        'offers_quantity' => $group->count(),
                        'total_price' => round((float)$group->sum('price'), 2),
                        'average_price' => round((float)$group->average('price'), 2),
                    ];
                })->values(),
        ]);
    }
}
