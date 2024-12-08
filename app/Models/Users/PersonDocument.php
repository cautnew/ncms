<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PersonDocument extends Model
{
  use HasFactory;

  protected $table = 'person_documents';

  protected static function booted(): void
  {
    static::creating(function (PersonDocument $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
