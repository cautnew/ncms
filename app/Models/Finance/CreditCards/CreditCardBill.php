<?php

namespace App\Models\Finance\CreditCards;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreditCardBill extends Model
{
  use HasFactory;

  protected static function booted(): void
  {
    static::creating(function (CreditCardBill $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
