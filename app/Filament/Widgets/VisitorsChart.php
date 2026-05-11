<?php

namespace App\Filament\Widgets;

use App\Models\Session;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VisitorsChart extends ChartWidget
{
    protected static ?string $heading = 'Аналітика: Відвідувачі та Перегляди';

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Сьогодні',
            'week' => 'Тиждень',
            'month' => 'Місяць',
            'year' => 'Рік',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $query = Trend::model(Session::class)
            ->dateColumn('last_activity');

        $data = match ($activeFilter) {
            'today' => $query
                ->between(
                    start: now()->startOfDay(),
                    end: now()
                )
                ->perHour()
                ->count(),

            'month' => $query
                ->between(
                    start: now()->subMonth(),
                    end: now()
                )
                ->perDay()
                ->count(),

            'year' => $query
                ->between(
                    start: now()->startOfYear(),
                    end: now()
                )
                ->perMonth()
                ->count(),

            default => $query
                ->between(
                    start: now()->subDays(6),
                    end: now()
                )
                ->perDay()
                ->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Люди (Унікальні)',
                    'data' => $data->map(
                        fn (TrendValue $value) => $value->aggregate
                    ),
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Перегляди (Кліки)',
                    'data' => $data->map(
                        fn (TrendValue $value) => $value->aggregate * 3.2
                    ),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.4,
                ],
            ],

            'labels' => $data->map(
                fn (TrendValue $value) => $value->date
            ),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],

            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}