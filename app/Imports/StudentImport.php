<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, SkipsOnFailure, WithValidation, WithHeadingRow
{
    use SkipsFailures, Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (!Role::where('name', 'student')->exists()) {
            Role::create(['name' => 'student']);
        }

        $user = new User();
        $user->name = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
        $user->email = $row['email'];
        $user->password = Hash::make($row['birthday']);
        $user->save();

        $user->assignRole('student');

        return new Student([
            'user_id' => $user->id,
            'lrn' => $row['lrn'],
            'email' => $row['email'],
            'first_name' => $row['first_name'],
            'middle_name' => $row['middle_name'],
            'last_name' => $row['last_name'],
            'suffix' => $row['suffix'],
            'birthday' => $row['birthday']
        ]);
    }

    public function rules(): array
    {
        return [
            'lrn' => 'required|digits:12|min:12|max:12|unique:students,lrn',
            'email' => 'required|email|unique:students,email',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'required|date'
        ];
    }
}
