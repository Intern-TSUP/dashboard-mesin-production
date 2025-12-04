<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHasCustomRole extends Model
{
    use HasFactory;
    use UUIDAsPrimaryKey;
    use SoftDeletes;

    protected $guarded;

    // public function subDepartments()
    // {
    //     return $this->belongsTo(SubDepartment::class, 'id_sub_department', 'id');
    // }

    public function customRole()
    {
        return $this->belongsTo(CustomRole::class, 'custom_role_id', 'id');
    }
}
