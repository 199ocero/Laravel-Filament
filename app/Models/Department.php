<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campus_id',
        'name',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
