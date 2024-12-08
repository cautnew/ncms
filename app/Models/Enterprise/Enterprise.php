<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'email',
    'website',
    'is_active'
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
      'name' => 'string',
      'description' => 'string',
      'email' => 'string',
      'website' => 'string'
    ];
  }

  public static function findById($id)
  {
    return self::where('id', $id);
  }

  public static function findByName($name)
  {
    return self::where('name', $name);
  }

  public static function allAvaillable(bool $full=false)
  {
    return self::join('enterprise_managers', function ($join) {
      $join->on('enterprises.id', '=', 'enterprise_managers.enterprise_id')
        ->where('enterprise_managers.is_active', '=', true);
    })->where('enterprises.is_active', '=', true);
  }
}
