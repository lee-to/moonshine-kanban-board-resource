<x-moonshine::grid>
    @foreach($statuses as $status)
        <x-moonshine::column colSpan="4">
            <x-moonshine::box :title="$status->{$resource->statusTitleField()}">
                <ul x-data="sortable" data-parent_key="{{ $status->getKey() }}">
                    @if(isset($data[$status->getKey()]))
                        @foreach($data[$status->getKey()] as $item)
                            <li data-id="{{ $item->getItem()->getKey() }}">
                                <x-moonshine::card
                                    class="handle"
                                    :title="$item->getItem()->{$resource->titleField()}"
                                >
                                    <x-slot:actions>
                                        <div class="flex items-center justify-end gap-2">
                                            @include('moonshine::crud.shared.item-actions', [
                                                'resource' => $item,
                                                'except' => ['show']
                                            ])
                                        </div>
                                    </x-slot:actions>
                                </x-moonshine::card>
                                <hr class="divider" />
                            </li>
                        @endforeach
                    @endif
                </ul>
            </x-moonshine::box>
        </x-moonshine::column>
    @endforeach
</x-moonshine::grid>

<script>
    function sortable() {
        return {
            init() {
                Sortable.create(this.$el, {
                    group: {
                        name: 'nested'
                    },
                    handle: '.handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    dataIdAttr: 'data-id',

                    onSort: async function (evt) {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('id', evt.item.dataset.id);
                        formData.append('parent', evt.to.dataset.parent_key);
                        formData.append('index', evt.newIndex);
                        formData.append('data', this.toArray());

                        await fetch('{{ $resource->route('kanban') }}', {
                            body: formData,
                            method: "post",
                        })
                    }
                });
            }
        }
    }
</script>
