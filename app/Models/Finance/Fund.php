<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Fund extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'name',
    'description',
    'balance'
  ];

  protected static function booted(): void
  {
    static::creating(function (Fund $model) {
      $model->id = (string) Str::uuid();
    });
  }
}
