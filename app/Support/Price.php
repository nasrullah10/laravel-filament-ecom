<?php

namespace App\Support;

class Price
{
    public static function currency(): string
    {
        return session('display_currency') === 'SAR' && is_numeric(session('pkr_sar_rate'))
            && (float) session('pkr_sar_rate') > 0 ? 'SAR' : 'PKR';
    }

    public static function format(int|float|string|null $amount): string
    {
        $currency = self::currency();
        $rate = $currency === 'SAR' ? (float) session('pkr_sar_rate') : 1;

        $number = number_format((float) $amount * $rate, 2, '.', ',');

        return $currency.' '.$number;
    }
}
