<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'effective_date',
    'amount'
  ];

  protected static function booted(): void
  {
    static::creating(function (Invoice $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
