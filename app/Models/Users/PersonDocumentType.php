<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PersonDocumentType extends Model
{
  use HasFactory;

  protected $table = 'person_document_types';

  protected $fillable = [
    'name',
    'description',
    'code',
    'is_active'
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'id' => 'string',
      'is_active' => 'boolean'
    ];
  }

  protected static function booted(): void
  {
    static::creating(function (PersonDocumentType $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
