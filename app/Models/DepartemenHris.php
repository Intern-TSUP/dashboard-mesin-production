<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartemenHris extends Model
{
    use HasFactory, UUIDAsPrimaryKey;

    protected $guarded;

    public function lines()
    {
        return $this->hasMany(Line::class, 'empOrg', 'EmpOrg');
    }
}
