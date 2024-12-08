<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Person extends Model
{
  /** @use HasFactory<\Database\Factories\User\PersonFactory> */
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
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

  protected static function booted(): void
  {
    static::creating(function (Person $person) {
      $person->id = (string) Str::uuid();
    });
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
