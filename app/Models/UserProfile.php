<?php

namespace App\Models;

use App\Traits\AuditTrailable;
use App\Traits\UUIDAsPrimaryKey;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory, UUIDAsPrimaryKey;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function line()
    {
        return $this->belongsTo(Line::class, 'line_id', 'id');
    }
}
