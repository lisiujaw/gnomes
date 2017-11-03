<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\GnomeException;

/**
 * Gnome model
 *
 * @table gnomes
 */
class Gnome extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gnomes';

    /**
     * Return an inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Return User model associated to gnome
     *
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Associate user to gnome
     *
     * @param User $user
     * @return self
     */
    public function setUser(User $user)
    {
        return $this->user()
            ->associate($user);
    }

    /**
     * Return gnome name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of gnome
     *
     * @param string $name
     * @return self
     * @throws GnomeException
     */
    public function setName(string $name) : Gnome
    {
        if (strlen($name) == 0 || strlen($name) > 255) {
            throw new GnomeException('Gnome name lenght must be greather than 0 and less than 255');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Return gnome strength
     *
     * @return integer
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * Set strength of gnome
     *
     * @param integer $strength
     * @return self
     * @throws GnomeException
     */
    public function setStrength(integer $strength) : Gnome
    {
        if ($strength < 0 || $strength > 100) {
            throw new GnomeException('Gnome strength must be greather than or equal 0 and less than or equal 100');
        }

        $this->strength = $strength;

        return $this;
    }

    /**
     * Return gnome age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set age of gnome
     *
     * @param integer $age
     * @return self
     * @throws GnomeException
     */
    public function setAge(integer $age) : Gnome
    {
        if ($age < 0 || $age > 100) {
            throw new GnomeException('Gnome age must be greather than or equal 0 and less than or equal 100');
        }

        $this->age = $age;

        return $this;
    }

    /**
     * Return avatar file name
     *
     * @return string
     */
    public function getAvatarFileName()
    {
        return $this->avatar_file;
    }

    /**
     * Set avatar file name
     *
     * @param string $avatarFileName
     * @return self
     */
    public function setAvatarFileName(string $avatarFileName) : Gnome
    {
        $this->avatar_file = $avatarFileName;

        return $this;
    }
}
