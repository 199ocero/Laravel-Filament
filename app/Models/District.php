<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function campus()
    {
        return $this->hasMany(Campus::class);
    }

    public function schoolYear()
    {
        return $this->hasMany(SchoolYear::class);
    }

    public function yearLevel()
    {
        return $this->hasMany(YearLevel::class);
    }
}
