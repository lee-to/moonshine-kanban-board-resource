## MoonShine kanban board resource

<p align="center">
<a href="https://moonshine-laravel.com" target="_blank">
<img src="https://github.com/lee-to/moonshine-kanban-board-resource/blob/2.x/art/screenshot.png">
</a>
</p>

### Requirements

- MoonShine v3.0+

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
    protected string $title = 'title';

    protected string $sortColumn = 'sorting';

    // ... fields, model, etc ...

    public function statuses(): Collection
    {
        return Status::query()
            ->orderBy('sorting')
            ->pluck('name', 'id');
    }

    public function foreignKey(): string
    {
        return 'status_id';
    }

    // ...
}
```
