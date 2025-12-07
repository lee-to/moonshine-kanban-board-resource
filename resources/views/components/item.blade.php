@php
    /** @var \Leeto\MoonShineKanBan\DTOs\KanbanItem $item */
@endphp
@props([
    'item',
    'buttons' => null, // Callback or HTML
])

<x-moonshine::card
    class="handle cursor-pointer rounded-md transition-all duration-200 hover:shadow-md border border-transparent hover:border-gray-200 dark:hover:border-dark-500 overflow-visible w-80"
    x-sortable-item="{{ $item->id }}"
    :thumbnail="$item->thumbnail ?? ''"
    :title="$item->title ?? $item->name ?? 'Без названия'"
    :subtitle="$item->subtitle"
>
    <x-slot:header>
        @if(!empty($item->labels))
            <div class="flex flex-wrap gap-1">
                @foreach($item->labels as $label)
                    <x-moonshine::badge :color="$label['color'] ?? 'gray'">
                        {{ $label['label'] }}
                    </x-moonshine::badge>
                @endforeach
            </div>
        @endif
    </x-slot:header>

    {{-- avatar + metrics --}}
    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100 dark:border-dark-500/50">

        {{-- avatar --}}
        @if($item->user && ($item->user['avatar'] ?? false))
            <a @if(!empty($item->user['url'])) href="{{ $item->user['url'] }}" @endif class="shrink-0" title="{{ $item->user['name'] ?? '' }}">
                <img src="{{ $item->user['avatar'] }}" alt="{{ $item->user['name'] ?? '' }}"
                     class="w-6 h-6 rounded-full object-cover ring-2 ring-white dark:ring-dark-600">
            </a>
        @else
            <div></div>
        @endif

        {{-- metrics --}}
        <div class="flex items-center gap-3 text-gray-400 dark:text-gray-500 text-xs">

            @foreach($item->meta as $value)
                <div class="flex items-center gap-1" title="{{ $value['label'] }}">
                    <x-moonshine::icon icon="{{ $value['icon'] }}" size="3"/>
                    <span>{{ $value['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <x-slot:actions>
        @if($item->buttons)
            @foreach($item->buttons as $button)
                @if(is_callable($button))
                    {!! $button($item) !!}
                @else
                    {!! $button !!}
                @endif
            @endforeach
        @endif

        @if(is_callable($buttons))
            {!! $buttons($item->model ?? $item) !!}
        @elseif($buttons)
            {!! $buttons !!}
        @endif
    </x-slot:actions>
</x-moonshine::card>
