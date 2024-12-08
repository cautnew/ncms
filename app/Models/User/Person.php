<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
  use HasFactory;

  protected $table = 'people';

  protected $fillable = [
    'name',
    'lastname',
    'birthdate',
    'user_id'
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'id' => 'string',
      'user_id' => 'string'
    ];
  }

  public static function findById(string $id)
  {
    return self::where('id', '=', $id);
  }

  public static function findByUserId(string $user_id)
  {
    return self::where('user_id', '=', $user_id);
  }
}
