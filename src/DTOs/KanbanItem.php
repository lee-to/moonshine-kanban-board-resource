<?php

namespace Leeto\MoonShineKanBan\DTOs;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Support\Traits\Makeable;


/**
 * DTO для элемента канбан-доски
 * @method static KanbanItem make(int $id, string $title, string $status)
 */
class KanbanItem
{
    use Makeable;

    public int $id;

    /** Заголовок карточки */
    public string $title;

    public ?Model $model = null;

    /** Дополнительный текст (опционально, 1–2 строки) */
    public ?string $subtitle = null;

    /** Превью-картинка / миниатюра */
    public ?string $thumbnail = null;

    /** Метки (цветные полосы), Trello-style */
    public array $labels = []; // [['label' => 'Design', 'color' => 'purple'], ...]

    /** Данные пользователя / исполнителя */
    public ?array $user = [
        'name' => null,
        'avatar' => null,
        'url' => null,
    ];

    /** Служебные счётчики, иконки справа */
    public array $meta = [];

    /** Ключ статуса (какой колонке принадлежит) */
    public string $status;

    /** Любые кастомные дополнительные данные */
    public array $extra = [];

    public array $buttons = [];

    public function __construct(int $id, string $title, string $status)
    {
        $this->id = $id;
        $this->title = $title;
        $this->status = $status;
    }

    public function setSubtitle(?string $subtitle): KanbanItem
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function setThumbnail(?string $thumbnail): KanbanItem
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function setLabels(array $labels): KanbanItem
    {
        $this->labels = $labels;
        return $this;
    }

    public function addLabel(string $label, string $color): KanbanItem
    {
        $this->labels[] = [
            'label' => $label,
            'color' => $color,
        ];
        return $this;
    }

    public function setUser(string $avatar, ?string $name = null, ?string $url = null): KanbanItem
    {
        $this->user = [
            'name' => $name,
            'avatar' => $avatar,
            'url' => $url,
        ];
        return $this;
    }

    public function setMeta(array $meta): KanbanItem
    {
        $this->meta = $meta;
        return $this;
    }

    public function addMeta(string $icon, string $label): KanbanItem
    {
        $this->meta[] = [
            'icon' => $icon,
            'label' => $label,
        ];
        return $this;
    }

    public function setButtons(array $buttons): KanbanItem
    {
        $this->buttons = $buttons;
        return $this;
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;
        return $this;
    }
}
