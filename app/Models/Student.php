<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lrn',
        'email',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthday'
    ];

    public function getFullNameAttribute()
    {
        $name = $this->first_name;

        if (!empty($this->middle_name)) {
            $name .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }

        if (!empty($this->last_name)) {
            $name .= ' ' . $this->last_name;
        }

        if (!empty($this->suffix)) {
            $name .= ' ' . $this->suffix;
        }

        return $name;
    }
}
