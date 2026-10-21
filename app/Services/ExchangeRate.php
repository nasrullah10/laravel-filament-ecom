<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRate
{
    public function pkrToSar(): float
    {
        return Cache::remember('exchange-rate.pkr-sar', now()->addDay(), function () {
            $data = Http::connectTimeout(3)->timeout(8)
                ->get('https://open.er-api.com/v6/latest/PKR')->throw()->json();
            $rate = $data['rates']['SAR'] ?? null;
            if (($data['result'] ?? null) !== 'success' || ($data['base_code'] ?? null) !== 'PKR'
                || ! is_numeric($rate) || ! is_finite((float) $rate) || $rate <= 0) {
                throw new RuntimeException('Invalid PKR/SAR exchange rate.');
            }

            return (float) $rate;
        });
    }
}
