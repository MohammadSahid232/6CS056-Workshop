<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'duration', 'fee', 'difficulty', 'is_active'])]
class Course extends Model
{
    public const DIFFICULTIES = ['Easy', 'Medium', 'Hard'];

    protected function casts(): array
    {
        return [
            'duration' => 'integer',
            'fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
