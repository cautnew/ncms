<?php

namespace App\Models\Elements;

use Database\Factories\Elements\ElementTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property string $class
 * @property string $group
 * @property string $icon
 * @property string $template_code
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property string|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class ElementType extends Model
{
    /** @use HasFactory<ElementTypeFactory> */
    use HasFactory;
}
