<?php

namespace Leeto\MoonShineKanBan\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Leeto\MoonShineKanBan\Resources\KanBanResource;

class KanBanComponent extends Component
{
    public function __construct(
        public KanBanResource $resource,
        public Collection $items
    ) {
    }


    public function render(): View
    {
        return view('moonshine-kanban::components.kanban-component')
            ->with(
                'statuses',
                $this->resource->statuses()
            )
            ->with(
                'data',
                $this->resource->perform($this->items)
            );
    }
}
