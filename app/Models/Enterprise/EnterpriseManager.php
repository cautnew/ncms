<?php

namespace App\Models\Enterprise;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterpriseManager extends Model
{
  use HasFactory;

  protected $fillable = [
    'enterprise_id',
    'user_id',
    'status'
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
      'enterprise_id' => 'string',
      'user_id' => 'string',
      'status' => 'boolean',
    ];
  }

  public static function findByUserId(string $userId='')
  {
    return self::where('user_id', $userId);
  }

  public static function findByEnterpriseId(string $enterpriseId='')
  {
    return self::where('enterprise_id', $enterpriseId);
  }
}
