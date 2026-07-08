<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $blockable_type
 * @property int $blockable_id
 * @property string $type
 * @property array<string, mixed> $data
 * @property int $order
 */
#[Fillable(['type', 'data', 'order'])]
class ContentBlock extends Model
{
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'order' => 'integer',
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function labelFor(string $type): string
    {
        return match ($type) {
            'hero' => 'Hero',
            'banner' => 'Banner de chamada',
            'image' => 'Imagem',
            'text' => 'Texto',
            'highlight' => 'Destaque',
            'stat' => 'Número',
            'testimonial' => 'Depoimento',
            'articles_grid' => 'Grade de artigos (automática)',
            'faq_list' => 'Lista de perguntas (automática)',
            default => $type,
        };
    }

    /**
     * Definicao dos campos de cada tipo de bloco: usada tanto para montar as
     * regras de validacao quanto para o formulario React renderizar so os
     * campos daquele tipo.
     *
     * @return array<int, array{name: string, label: string, type: string, required: bool}>
     */
    public static function fieldsFor(string $type): array
    {
        return match ($type) {
            'hero' => [
                ['name' => 'title', 'label' => 'Título', 'type' => 'text', 'required' => true],
                ['name' => 'subtitle', 'label' => 'Subtítulo', 'type' => 'textarea', 'required' => true],
                ['name' => 'button_label', 'label' => 'Texto do botão', 'type' => 'text', 'required' => true],
                ['name' => 'button_href', 'label' => 'Link do botão', 'type' => 'text', 'required' => true],
                ['name' => 'image', 'label' => 'URL da imagem (opcional)', 'type' => 'text', 'required' => false],
            ],
            'banner' => [
                ['name' => 'title', 'label' => 'Título', 'type' => 'text', 'required' => true],
                ['name' => 'text', 'label' => 'Texto', 'type' => 'textarea', 'required' => true],
                ['name' => 'button_label', 'label' => 'Texto do botão', 'type' => 'text', 'required' => true],
                ['name' => 'button_href', 'label' => 'Link do botão', 'type' => 'text', 'required' => true],
            ],
            'image' => [
                ['name' => 'src', 'label' => 'URL da imagem', 'type' => 'text', 'required' => true],
                ['name' => 'alt', 'label' => 'Texto alternativo', 'type' => 'text', 'required' => true],
                ['name' => 'caption', 'label' => 'Legenda (opcional)', 'type' => 'text', 'required' => false],
            ],
            'text' => [
                ['name' => 'heading', 'label' => 'Título (opcional)', 'type' => 'text', 'required' => false],
                ['name' => 'body', 'label' => 'Texto', 'type' => 'textarea', 'required' => true],
            ],
            'highlight' => [
                ['name' => 'icon', 'label' => 'Ícone (emoji)', 'type' => 'text', 'required' => true],
                ['name' => 'title', 'label' => 'Título', 'type' => 'text', 'required' => true],
                ['name' => 'text', 'label' => 'Texto', 'type' => 'textarea', 'required' => true],
            ],
            'stat' => [
                ['name' => 'number', 'label' => 'Número', 'type' => 'text', 'required' => true],
                ['name' => 'label', 'label' => 'Legenda', 'type' => 'text', 'required' => true],
            ],
            'testimonial' => [
                ['name' => 'quote', 'label' => 'Depoimento', 'type' => 'textarea', 'required' => true],
                ['name' => 'author', 'label' => 'Autor', 'type' => 'text', 'required' => true],
            ],
            'articles_grid' => [
                ['name' => 'title', 'label' => 'Título da seção', 'type' => 'text', 'required' => true],
                ['name' => 'limit', 'label' => 'Quantidade de artigos', 'type' => 'number', 'required' => true],
            ],
            'faq_list' => [],
            default => [],
        };
    }

    /**
     * @return array<string, mixed>
     */
    public static function rulesFor(string $type): array
    {
        $rules = [];

        foreach (self::fieldsFor($type) as $field) {
            $type1 = $field['type'] === 'number' ? 'integer' : 'string';
            $rules['data.'.$field['name']] = [$field['required'] ? 'required' : 'nullable', $type1];
        }

        return $rules;
    }

    public function summary(): string
    {
        return match ($this->type) {
            'hero', 'banner' => (string) $this->data['title'],
            'image' => (string) $this->data['alt'],
            'text' => (string) ($this->data['heading'] ?: str($this->data['body'])->limit(60)),
            'highlight' => (string) $this->data['title'],
            'stat' => "{$this->data['number']} — {$this->data['label']}",
            'testimonial' => (string) $this->data['author'],
            'articles_grid' => (string) $this->data['title'],
            'faq_list' => 'Perguntas frequentes cadastradas',
            default => '',
        };
    }
}
