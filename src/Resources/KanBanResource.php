<?php

namespace Leeto\MoonShineKanBan\Resources;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Leeto\MoonShineKanBan\View\Components\KanBanComponent;
use MoonShine\AssetManager\Js;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Laravel\MoonShineRequest;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Support\Enums\SortDirection;

abstract class KanBanResource extends ModelResource
{
    protected bool $usePagination = false;

    protected SortDirection $sortDirection = SortDirection::ASC;

    protected ?string $description = null;

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    abstract public function statuses(): Collection;

    abstract public function foreignKey(): string;

    protected function onLoad(): void
    {
        parent::onLoad();

        $this->getAssetManager()->add(
            Js::make('https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js')
        );
    }

    public function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return KanBanComponent::make($this, $this->getItems());
    }

    public function getListEventName(?string $name = null, array $params = []): string
    {
        return AlpineJs::event(JsEvent::FRAGMENT_UPDATED, 'crud-list');
    }

    public function sort(MoonShineRequest $request): Response
    {
        $keyName = $request->getResource()?->getModel()?->getKeyName();
        $model = $request->getResource()?->getModel();

        $model->newModelQuery()
            ->firstWhere($keyName, $request->input('id'))
            ?->update([
                $this->getSortColumn() => $request->integer('index'),
                $this->foreignKey() => $request->input('parent')
            ]);

        if ($request->filled('data')) {
            $ids = $request->str('data')
                ->explode(',')
                ->values();

            foreach ($ids as $index => $id) {
                $query = $model->newModelQuery()->where($keyName, $id);

                if ($request->has('parent')) {
                    $query->where($this->foreignKey(), $request->input('parent'));
                }

                $query->update([
                    $this->getSortColumn() => (int)$index,
                ]);
            }
        }

        return response()->noContent();
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

}
