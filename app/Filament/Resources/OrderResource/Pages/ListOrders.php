<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Query\Builder;
use App\Filament\Resources\OrderResource\Widgets\OrderStatus;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatus::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            null=>Tab::make('all'),
            'new'=>Tab::make('new')->query(function (Builder $query): Builder {
                return $query->where('status', 'new');
            }),
            'processing'=>Tab::make('processing')->query(function (Builder $query): Builder {
                return $query->where('status', 'processing');
            }),
            'shipped'=>Tab::make('shipped')->query(function (Builder $query): Builder {
                return $query->where('status', 'shipped');
            }),
            'delivered'=>Tab::make('delivered')->query(function (Builder $query): Builder {
                return $query->where('status', 'delivered');
            }),
            'cancelled'=>Tab::make('cancelled')->query(function (Builder $query): Builder {
                return $query->where('status', 'cancelled');
            }),
        ];
    }
}
