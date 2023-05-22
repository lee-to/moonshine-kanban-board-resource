## MoonShine kanban board resource

<p align="center">
<a href="https://moonshine.cutcode.dev" target="_blank">
<img src="https://github.com/lee-to/moonshine-kanban-board-resource/art/screenshot.png">
</a>
</p>

### Requirements

- MoonShine v1.57+

### Installation

```shell
composer require lee-to/moonshine-kanban-board-resource
```

### Get started

Example usage

```php
use Leeto\MoonShineKanBan\Resources\KanBanResource;

class TaskResource extends KanBanResource
{
    public string $titleField = 'title';

    public static string $orderField = 'sorting';

    // ... fields, model, etc ...

    public function statuses(): Collection
    {
        return Status::query()
            ->orderBy($this->statusSortKey())
            ->get();
    }

    public function statusTitleField(): string
    {
        return 'title';
    }

    public function statusKey(): string
    {
        return 'status_id';
    }

    public function statusSortKey(): string
    {
        return 'sorting';
    }

    public function sortKey(): string
    {
        return 'sorting';
    }

    // ...
}
```
