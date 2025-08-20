<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Roles extends Model
{
    use HasFactory, Notifiable, UUIDAsPrimaryKey, SoftDeletes;

    protected $guarded;

    public function permission()
    {
        return $this->hasMany(Permissions::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsTo(Permissions::class, 'id', 'role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
