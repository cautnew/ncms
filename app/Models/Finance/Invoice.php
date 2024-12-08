<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'effective_date',
    'amount'
  ];
}
