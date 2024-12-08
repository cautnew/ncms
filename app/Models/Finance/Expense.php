<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Expense extends Model
{
  use HasFactory;

  protected static function booted(): void
  {
    static::creating(function (Expense $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
