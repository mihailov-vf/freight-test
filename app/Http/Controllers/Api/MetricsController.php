<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->query();
    }
}