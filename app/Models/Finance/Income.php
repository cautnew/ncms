<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Income extends Model
{
  use HasFactory;

  protected static function booted(): void
  {
    static::creating(function (Income $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
