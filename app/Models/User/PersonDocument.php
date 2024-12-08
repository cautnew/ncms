<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonDocument extends Model
{
  use HasFactory;

  protected $table = 'person_documents';
}
