<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'address', 'date_of_birth'])]
class Student extends Model
{
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }
}
