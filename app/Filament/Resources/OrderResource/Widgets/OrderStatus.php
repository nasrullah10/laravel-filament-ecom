<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrderStatus extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::where('status', 'new')->count())->color('info'),
            Stat::make('Order Processing', Order::where('status', 'processing')->count())->color('warning'),
            Stat::make('Order Shipped', Order::where('status', 'shipped')->count())->color('success'),
            Stat::make('Order Delivered', Order::where('status', 'delivered')->count())->color('success'),
            Stat::make('Order Cancelled', Order::where('status', 'cancelled')->count())->color('danger'),
            Stat::make('Total Orders', Order::count())->color('primary'),
            Stat::make('Average Price',Number::currency(Order::avg('grand_total'),'PKR'))->color('success'),
        ];  
    }
}
