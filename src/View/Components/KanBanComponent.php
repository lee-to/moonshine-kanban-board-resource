<?php

namespace Leeto\MoonShineKanBan\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Leeto\MoonShineKanBan\Resources\KanBanResource;
use MoonShine\UI\Components\ActionGroup;
use MoonShine\UI\Components\MoonShineComponent;

class KanBanComponent extends MoonShineComponent
{
    protected string $view = 'moonshine-kanban::components.kanban-component';

    public function __construct(
        public KanBanResource $resource,
        public Collection $items
    ) {
        parent::__construct();
    }

    public function transformData(Collection $items): Collection
    {
        return $items->mapToGroups(fn(Model $item) => [$item->{$this->resource->foreignKey()} => $item]);
    }

    protected function viewData(): array
    {
        return [
            'statuses' => $this->resource->statuses(),
            'data' => $this->transformData($this->items),
            'column' => $this->resource->getColumn(),
            'sortRoute' => $this->resource->getAsyncMethodUrl('sort'),
            'buttons' => fn(Model $item) => ActionGroup::make(
                $this->resource->setItem($item)->getIndexButtons()->fill(
                    $this->resource->setItem($item)->getCastedData()
                )->onlyVisible()->withoutBulk()
            ),
        ];
    }
}
