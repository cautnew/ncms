<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
