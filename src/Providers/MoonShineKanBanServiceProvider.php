<?php

declare(strict_types=1);

namespace Leeto\MoonShineKanBan\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

final class MoonShineKanBanServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'moonshine-kanban');

        Blade::withoutDoubleEncoding();
        Blade::componentNamespace('Leeto\MoonShineKanBan\View\Components', 'moonshine-kanban');

        $this->commands([]);
    }
}
