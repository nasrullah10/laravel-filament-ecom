<?php

namespace App\Support;

use App\Models\Product;

class Price
{
    public static function currency(): string
    {
        return session('display_currency') === 'SAR' && is_numeric(session('pkr_sar_rate'))
            && (float) session('pkr_sar_rate') > 0 ? 'SAR' : 'PKR';
    }

    public static function sar(int|float|string|null $amount, int|float|string|null $manual = null): float
    {
        return round($manual !== null ? (float) $manual : (float) $amount * (float) session('pkr_sar_rate', 0), 2);
    }

    public static function format(int|float|string|null $amount, int|float|string|null $sar = null): string
    {
        $currency = self::currency();
        $value = $currency === 'SAR' ? self::sar($amount, $sar) : (float) $amount;

        return $currency.' '.number_format($value, 2, '.', ',');
    }

    public static function product(Product $product, bool $compare = false): string
    {
        return self::format($compare ? $product->compare_price : $product->price,
            $compare ? $product->compare_price_sar : $product->price_sar);
    }

    public static function hasCompare(Product $product): bool
    {
        if (self::currency() === 'SAR' && $product->price_sar !== null) {
            return $product->compare_price_sar !== null && $product->compare_price_sar > $product->price_sar;
        }

        return $product->compare_price !== null && $product->compare_price > $product->price;
    }

    public static function cartTotal(iterable $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += self::sar($item['unit_amount'], $item['unit_amount_sar'] ?? null) * $item['quantity'];
        }
        return round($total, 2);
    }
}