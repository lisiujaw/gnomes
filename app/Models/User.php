<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;

/**
 * User model
 *
 * @table users
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * Return a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gnomes()
    {
        return $this->hasMany(Gnome::class, 'user_id');
    }

    /**
     * Return Collection of user gnomes
     *
     * @return Collection
     */
    public function getGnomes()
    {
        return $this->gnomes;
    }
}
