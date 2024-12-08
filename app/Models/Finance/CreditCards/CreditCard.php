<?php

namespace App\Models\Finance\CreditCards;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreditCard extends Model
{
  use HasFactory;

  protected static function booted(): void
  {
    static::creating(function (CreditCard $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
