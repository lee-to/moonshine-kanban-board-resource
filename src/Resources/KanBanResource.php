<?php

namespace Leeto\MoonShineKanBan\Resources;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use MoonShine\Http\Requests\Resources\ViewAnyFormRequest;
use MoonShine\Resources\Resource;

abstract class KanBanResource extends Resource
{
    protected bool $usePagination = false;
    protected string $itemsView = 'moonshine-kanban::items';

    public string $titleField = 'id';

    public static string $orderType = 'ASC';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    abstract public function statuses(): Collection;

    abstract public function statusTitleField(): string;

    abstract public function statusKey(): string;

    abstract public function statusSortKey(): string;

    abstract public function sortKey(): string;

    public function search(): array
    {
        return [];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [];
    }


    public function perform(Collection $resources): array
    {
        $performed = [];

        foreach ($resources as $resource) {
            $item = $resource->getItem();
            $performed[$item->{$this->statusKey()}][] = $resource;
        }

        foreach ($performed as $statusId => $items) {
            $performed[$statusId] = collect($items)->sortBy(function (Resource $item) {
                return $item->getItem()->{$this->statusSortKey()};
            })->toArray();
        }


        return $performed;
    }

    public function resolveRoutes(): void
    {
        parent::resolveRoutes();

        Route::prefix('resource')->group(function () {
            Route::post("{$this->uriKey()}/kanban", function (ViewAnyFormRequest $request) {
                $keyName = $request->getResource()->getModel()->getKeyName();
                $model = $request->getResource()->getModel();

                $model->newQuery()
                    ->where($keyName, $request->get('id'))
                    ->update([
                        $this->sortKey() => $request->integer('index'),
                        $this->statusKey() => $request->get('parent')
                    ]);


                $caseStatement = $request->str('data')
                    ->explode(',')
                    ->filter()
                    ->implode(fn($id, $index) => "WHEN {$id} THEN {$index} ");

                if ($caseStatement) {
                    $model->newQuery()
                        ->update([
                            $this->statusKey() => $request->get('parent'),
                            $this->sortKey() => DB::raw("CASE $keyName $caseStatement END")
                        ]);
                }

                return response()->noContent();
            })->name($this->routeNameAlias().'.kanban');
        });
    }
}
