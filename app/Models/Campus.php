<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'district_id',
        'name',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function schoolYear()
    {
        return $this->hasMany(SchoolYear::class);
    }

    public function yearLevel()
    {
        return $this->hasMany(YearLevel::class);
    }

    public function semester()
    {
        return $this->hasMany(Semester::class);
    }

    public function department()
    {
        return $this->hasMany(Semester::class);
    }

    public function section()
    {
        return $this->hasMany(Section::class);
    }
}
