<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRate;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __invoke(Request $request, string $currency, ExchangeRate $rates)
    {
        abort_unless(in_array($currency, ['PKR', 'SAR'], true), 404);
        $path = $request->input('return', '/');
        if (! is_string($path) || ! str_starts_with($path, '/') || str_starts_with($path, '//')
            || preg_match('/[\\\\\x00-\x1F\x7F]/', $path)) {
            $path = '/';
        }

        if ($currency === 'SAR') {
            try {
                $request->session()->put('pkr_sar_rate', $rates->pkrToSar());
            } catch (\Throwable $exception) {
                report($exception);

                return redirect($path)->with('error', 'Riyal conversion is temporarily unavailable. Please try again.');
            }
        }
        $request->session()->put('display_currency', $currency);
        $request->session()->forget('locale');

        return redirect($path);
    }
}
