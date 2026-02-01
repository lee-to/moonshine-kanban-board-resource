## MoonShine kanban board resource

<p align="center">
<a href="https://moonshine-laravel.com" target="_blank">
<img src="https://github.com/lee-to/moonshine-kanban-board-resource/blob/2.x/art/screenshot.png">
</a>
</p>

### Requirements

- v0 - 2.1 - MoonShine v3.0
- v2.2+ - MoonShine v4.0

### Installation

```shell
composer require lee-to/moonshine-kanban-board-resource
```

## Usage

### Basic Usage

Example usage

```php
use Leeto\MoonShineKanBan\Resources\KanBanResource;

class TaskResource extends KanBanResource
{
    protected string $title = 'title';

    protected string $sortColumn = 'sorting';

    protected ?string $description = 'description'; // Card description field (optional)

    // ... fields, model, etc ...

    protected function pages(): array
    {
        return [
            TaskIndexPage::class,
            FormPageContract::class,
        ];
    }

    public function statuses(): \Illuminate\Support\Collection
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

IndexPage

```php
final class TaskIndexPage extends IndexPage
{
    protected function modifyListComponent(ComponentContract $component): ComponentContract
    {
        return KanBanComponent::make($this->getResource(), $this->getResource()->getItems());
    }
}
```

### Advanced Usage with DTO

You can use the DTO approach for more flexible customization:

```php
use Leeto\MoonShineKanBan\DTOs\KanbanItem;

public function getItems(): iterable
{
    $items = new Collection;

    foreach (parent::getItems() as $task) {
        $item = KanbanItem::make(
            id: $task->id,
            title: $task->title,
            status: $this->foreignKey(),
        )
            ->setModel($task)
            ->setSubtitle(str($task->description)->limit(50)->value())
            //->setThumbnail(asset('images/template.jpg'))
            ->addLabel(fake()->word(), 'red')
            ->addLabel(fake()->word(), 'green')
            ->setUser(
                avatar: asset('images/template.jpg'),
                name: $task->user->name,
            )
            ->addMeta('user', $task->user->name)
            ->addMeta('chat-bubble-left', (string) random_int(0, 100))
            ->addMeta('users', (string) random_int(0, 50))
            ->setButtons([])
        ;

        $items->push($item);
    }

    return $items;
}
```

## Features

### KanbanItem DTO Properties

The `KanbanItem` DTO provides extensive customization options:

#### Basic Properties
- `id`: Item identifier (required)
- `title`: Card title (required)
- `status`: Status key (required)
- `subtitle`: Optional subtitle text (1-2 lines)
- `thumbnail`: Preview image URL
- `model`: Associated Eloquent model

#### Visual Elements
- `labels`: Array of colored labels (Trello-style)
- `user`: User information with avatar, name and URL
- `meta`: Array of metadata with icons and labels
- `buttons`: Custom action buttons

#### Methods
- `setSubtitle(?string $subtitle)`: Set subtitle text
- `setThumbnail(?string $thumbnail)`: Set thumbnail image
- `addLabel(string $label, string $color)`: Add a colored label
- `setUser(string $avatar, ?string $name = null, ?string $url = null)`: Set user info
- `addMeta(string $icon, string $label)`: Add metadata with icon
- `setButtons(array $buttons)`: Set custom buttons
- `setModel(Model $model)`: Associate with Eloquent model

### Custom Buttons

You can define custom buttons using the `buttons()` method in IndexPage:

```php
/**
 * Action buttons for cards in kanban
 */
protected function buttons(): ListOf
{
    return [
        // ActionButton
    ];
}
```

### Horizontal Scrolling

The kanban board features horizontal scrolling with auto-scroll functionality:

- **Manual Scrolling**: Users can scroll horizontally through columns
- **Auto-scroll on Drag**: When dragging a card near the left or right edge, the board automatically scrolls
- **Smooth Animation**: Scrolling is animated for better user experience
- **Touch Support**: Works on touch devices with swipe gestures

### Component Structure

The view is split into three main components for customization:

1. **Kanban Component**: Main container with scroll functionality
2. **Column Component**: Individual column with header and card list
3. **Item Component**: Individual card with all visual elements

Each component can be customized independently by extending the base components.

