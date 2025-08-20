<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\AuditTrailable;
use App\Traits\UUIDAsPrimaryKey;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUIDAsPrimaryKey;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user', 'email');
    }

    public function notify()
    {
        return $this->notifications()->orderBy('created_at', 'desc')->get();
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'jobLvl', 'name');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
    
    public function sessions()
    {
        return $this->belongsTo(Session::class, 'id', 'user_id');
    }
}
