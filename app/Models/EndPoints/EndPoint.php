<?php

namespace App\Models\EndPoints;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EndPoint extends Model
{
  /** @use HasFactory<\Database\Factories\EndPoints\EndPointFactory> */
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'method',
    'route'
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
    ];
  }

  /**
   * The "booted" method of the model to set the ID for the
   * default UUID.
   *
   * @return void
   */
  protected static function booted(): void
  {
    static::creating(function (EndPoint $endPoint) {
      $endPoint->id = (string) Str::uuid();
    });
  }

  public static function getAllByMethod(string $method)
  {
    return self::where('method', '=', $method);
  }

  public static function findByRoute(string $route)
  {
    return self::where('route', '=', $route);
  }
}
