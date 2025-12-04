<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomRole extends Model
{
    use HasFactory, UUIDAsPrimaryKey;

    protected $guarded;

    public function permission()
    {
        return $this->hasMany(Permissions::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsTo(Permissions::class, 'id', 'role_id');
    }
}
