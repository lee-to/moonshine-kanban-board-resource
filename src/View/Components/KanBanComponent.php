<?php

namespace Leeto\MoonShineKanBan\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Leeto\MoonShineKanBan\DTOs\KanbanItem;
use Leeto\MoonShineKanBan\Resources\KanBanResource;
use MoonShine\UI\Collections\ActionButtons;
use MoonShine\UI\Components\ActionGroup;
use MoonShine\UI\Components\MoonShineComponent;

class KanBanComponent extends MoonShineComponent
{
    protected string $view = 'moonshine-kanban::components.kanban-component';


    /**
     * @var Collection<KanbanItem> $kanbanItems
     */
    private Collection $items;

    public function __construct(public KanBanResource $resource, Collection $items)
    {
        $this->items = collect();
        // Convert items to KanbanItem DTOs if they are not already
        foreach ($items as $item) {
            if ($item instanceof KanbanItem) {
                $this->items->push($item);
            } else {
                $dto = KanbanItem::make($item->id, $item->{$this->resource->getColumn()}, (string)$item->{$this->resource->foreignKey()});
                if ($this->resource->getDescription()) {
                    $dto->setSubtitle($item->{$this->resource->getDescription()});
                }
                $dto->setModel($item);
                $this->items->push($dto);
            }
        }
        parent::__construct();
    }

    public function transformData(Collection $items): Collection
    {
        return $items->mapToGroups(fn(KanbanItem $item) => [$item->status => $item]);
    }

    protected function viewData(): array
    {
        $buttons = fn(Model|KanbanItem $item) => $item instanceof Model
            // reversed compatibility, if an Eloquent Model is passed instead of KanbanItem
            ? ActionGroup::make(
                ActionButtons::make($this->resource->setItem($item)->getIndexButtons())
                    ->fill($this->resource->setItem($item)->getCastedData())
                    ->onlyVisible()
                    ->withoutBulk())
            : null;
        return [
            'statuses' => $this->resource->statuses(),
            'data' => $this->transformData($this->items),
            'column' => $this->resource->getColumn(),
            'description' => $this->resource->getDescription(),
            'sortRoute' => $this->resource->getAsyncMethodUrl('sort'),
            'buttons' => $buttons,
        ];
    }
}
