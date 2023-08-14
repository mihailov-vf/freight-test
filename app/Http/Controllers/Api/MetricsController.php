<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetricsReader;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MetricsController extends Controller
{
    public function __construct(private MetricsReader $metricsReader)
    {
    }

    public function __invoke(Request $request): Response
    {
        $metrics = $this->metricsReader->readMetricsFromLastQuotes((int)$request->query('last_quotes'));

        return $metrics->toResponse($request);
    }
}
