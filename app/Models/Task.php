<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'date_limit',
        'id_status',
        'id_priority',
        'id_user',
    ];

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function priority() {
        return $this->belongsTo(Priority::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
