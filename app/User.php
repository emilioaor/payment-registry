<?php

namespace App;

use App\Contract\SearchTrait;
use App\Contract\UuidGeneratorTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use UuidGeneratorTrait;
    use SearchTrait;

    /** Roles */
    const ROLE_ADMIN = 'administrator';
    const ROLE_FINANCES = 'finances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $search_fields = [
        'name', 'email'
    ];

    /**
     * Is admin?
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Is finances?
     *
     * @return bool
     */
    public function isFinances(): bool
    {
        return $this->isAdmin() || $this->role === self::ROLE_FINANCES;
    }
}
