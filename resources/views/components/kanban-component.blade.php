<div class="w-full overflow-hidden">
    <div
        class="w-full overflow-x-auto"
        style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;     overflow-x: scroll;"
        x-data="kanbanBoardScroll"
    >
        <div class="flex gap-4 pb-4 select-none items-start min-w-max">
            @foreach ($statuses as $key => $title)
                <x-moonshine-kanban::column
                    :title="$title"
                    :key="$key"
                    :items="$data[$key] ?? []"
                    :buttons="$buttons"
                    :sortRoute="$sortRoute"
                />
            @endforeach
        </div>
    </div>
</div>

<script>
    // Auto-scroll horizontally when dragging card near board edges
    function kanbanBoardScroll() {
        return {
            init() {
                const container = this.$el;
                const edge = 120;   // sensitive edge area in pixels
                const speed = 20;   // scroll speed in pixels

                document.addEventListener('dragover', (e) => {
                    const rect = container.getBoundingClientRect();
                    const x = e.clientX;

                    if (x < rect.left + edge) {
                        container.scrollLeft -= speed;
                    } else if (x > rect.right - edge) {
                        container.scrollLeft += speed;
                    }
                });
            }
        }
    }

    // Vertical auto-scroll inside column
    function kbSortable(sortRoute) {
        return {
            init() {
                const scrollSpeed = 15;
                const edgeSize = 80;
                const container = this.$el;

                Sortable.create(container, {
                    group: {name: 'kanban-group'},
                    animation: 150,
                    handle: '.handle',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    dataIdAttr: 'data-id',

                    onStart(evt) {
                        evt.item.classList.add('kanban-lift');
                    },
                    onEnd(evt) {
                        evt.item.classList.remove('kanban-lift');
                    },

                    onMove(evt) {
                        const rect = container.getBoundingClientRect();
                        const y = evt.originalEvent.clientY;

                        if (y < rect.top + edgeSize) {
                            container.scrollTop -= scrollSpeed;
                        } else if (y > rect.bottom - edgeSize) {
                            container.scrollTop += scrollSpeed;
                        }
                    },

                    async onSort(evt) {
                        let formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('id', evt.item.dataset.id);
                        formData.append('parent', evt.to.dataset.parent_key);
                        formData.append('index', evt.newIndex);
                        formData.append('data', this.toArray())

                        await fetch(sortRoute, {
                            method: 'POST',
                            body: formData,
                        });
                    }
                });
            }
        }
    }
</script>
