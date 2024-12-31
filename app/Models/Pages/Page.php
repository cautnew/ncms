<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
  /** @use HasFactory<\Database\Factories\Pages\PageFactory> */
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'pages';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'alias',
    'description',
    'user_id',
    'is_active'
  ];

  /**
   * The "booted" method of the model to set the ID for the
   * default UUID.
   *
   * @return void
   */
  protected static function booted(): void
  {
    static::creating(function (Page $page) {
      $page->id = (string) Str::uuid();
    });
  }

  public static function getAllByMethod(string $method)
  {
    return self::where('method', '=', $method);
  }

  public static function findByName(string $name)
  {
    return self::where('name', '=', $name);
  }
}
