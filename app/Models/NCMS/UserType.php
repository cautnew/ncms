<?php

namespace App\Models\NCMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    /** @use HasFactory<\Database\Factories\NCMS\UserTypeFactory> */
    use HasFactory;

    protected $table = "user_types";

    protected $fillable = [
        'name',
        'description',
        'acronym',
    ];

    public function findUserTypeByAcronym(string $acronym): self {
        return $this;
    }
}
