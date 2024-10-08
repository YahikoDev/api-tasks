<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id'); // Relación inversa
    }
}
