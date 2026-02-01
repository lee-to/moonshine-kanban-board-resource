@php
    /** @var \Leeto\MoonShineKanBan\DTOs\KanbanItem $item */

@endphp

@props([
    'title',
    'key',
    'items' => [],
    'buttons' => null,
    'sortRoute' => null,
    ])

<div class="flex-shrink-0 select-none" style="max-width: 450px;">
    <div class="bg-slate-100 dark:bg-dark-800 rounded-lg shadow-sm flex flex-col h-full">

        {{-- Header ---}}
        <div class="py-3 border-b border-gray-200 dark:border-dark-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-700 dark:text-gray-200 truncate">
                {{ $title }}
            </h3>
            <x-moonshine::badge color="gray">
                {{ count($items) }}
            </x-moonshine::badge>
        </div>

        <x-moonshine::layout.line-break />

        {{-- List cards --}}
        <div
            x-data="{ search: '', ...kbSortable({{ json_encode($sortRoute) }}) }"
            data-parent_key="{{ $key }}"
            class="flex flex-col gap-3 overflow-y-auto min-h-[150px] max-h-[calc(100vh-250px)]"
        >
            @foreach($items as $item)
                @php
                    $titleForFilter = $item->title ?? $item->name ?? '';
                    $filterString = mb_strtolower($titleForFilter);
                @endphp

                <div data-id="{{ $item->id }}" class="group w-full">

                    <x-moonshine-kanban::item
                        :item="$item"
                        :buttons="$buttons ?? null"
                    />
                </div>
            @endforeach
        </div>

    </div>
</div>
