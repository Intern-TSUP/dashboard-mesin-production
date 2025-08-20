<?php

namespace App\Models;

use App\Traits\AuditTrailable;
use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PermissionsLine extends Model
{
    use HasFactory, Notifiable, UUIDAsPrimaryKey;


    protected $guarded;
}
