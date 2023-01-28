<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'district_id',
        'campus_id',
        'name',
        'status_id',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
