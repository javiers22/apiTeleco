<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class bas_tercero extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $table = 'vis_usta_baster_cla';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'email', 'password',
    ];

     protected $hidden = [
        'password',
        'remember_token',
    ];
}
