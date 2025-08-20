<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, UUIDAsPrimaryKey;
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded;

    //untuk mengisi kolom 'inupby' dengan email user yang sedang login
    protected static function booted(): void
    {
        // method ini berjalan sebelum data baru dibuat
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->inupby = Auth::user()->email;
            }
        });

        // method ini berjalan sebelum data diupdate
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->inupby = Auth::user()->email;
            }
        });
    } 

    public function users()
    {
        return $this->hasMany(User::class, 'group_id', 'id');
    }
}
