<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\GnomeException;
use Illuminate\Http\UploadedFile;
use App\Models\Gnome;
use App\Models\User;
use Storage;
use Image;
use Auth;
use DB;

class GnomeService
{
    /**
     * Return user gnomes collection
     *
     * @param  User|null $user
     * @return Collection
     * @throws \Exception
     */
    public function getUserGnomes(User $user = null) : Collection
    {
        $user = $user ?? Auth::user();

        if (! is_a($user, User::class)) {
            throw new \Exception('Bad User');
        }

        return $user->getGnomes();
    }

    /**
     * Return gnome by id
     *
     * @param  int $id
     * @return Gnome
     * @throws \Exception
     */
    public function getGnomeById(int $id) : Gnome
    {
        try {
            return Gnome::findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Can not find gnome');
        }
    }

    /**
     * Create new gnome model
     *
     * @param  array  $attributes
     * @param  mixed $avatarFile
     * @param  User|null $user
     * @return Gnome
     * @throws \Exception
     */
    public function createGnome(array $attributes, $avatarFile, User $user = null) : Gnome
    {
        $user = $user ?? Auth::user();
        $gnome = (new Gnome($attributes))
            ->setUser($user)
            ->setAvatarFileName(
                $this->storeAvatar($avatarFile)
            );

        if ($gnome->save()) {
            return $gnome->makeHidden('user');
        }

        throw new \Exception('Can not save gnome');
    }

    /**
     * Update gnome data
     *
     * @param  Gnome  $gnome
     * @param  array  $attributes
     * @param  mixed|null $avatarFile
     * @return boolean
     * @throws \Exception|GnomeException
     */
    public function updateGnome(Gnome $gnome, array $attributes, $avatarFile = null) : bool
    {
        if (isset($attributes['name'])) {
            $gnome->setName($attributes['name']);
        }

        if (isset($attributes['age'])) {
            $gnome->setAge($attributes['age']);
        }

        if (isset($attributes['strength'])) {
            $gnome->setStrength($attributes['strength']);
        }

        if ($avatarFile != null) {
            $gnome->setAvatarFileName($this->storeAvatar($avatarFile));
        }

        return $gnome->save();
    }

    /**
     * Update gnome by id
     *
     * @param  int        $id
     * @param  array      $attributes
     * @param  mixed|null $avatarFile
     * @return boolean
     * @throws inherit
     */
    public function updateGnomeById(int $id, array $attributes, $avatarFile = null) : bool
    {
        return $this->updateGnome(
            $this->getGnomeById($id),
            $attributes,
            $avatarFile
        );
    }

    /**
     * Delete gnome
     *
     * @param  Gnome  $gnome
     * @return boolean
     */
    public function deleteGnome(Gnome $gnome) : bool
    {
        return $gnome->delete();
    }

    /**
     * Delete gnome by id
     *
     * @param  int $id
     * @return boolean
     * @throws \Exception
     */
    public function deleteGnomeById(int $id) : bool
    {
        return $this->deleteGnome(
            $this->getGnomeById($id)
        );
    }

    /**
     * Store avatar in disk storage and return file name
     *
     * @param  mixed $avatar
     * @return string
     * @throws \Exception
     */
    private function storeAvatar($avatar) : string
    {
        try {
            $image = Image::make($avatar)
                ->encode('jpg', 100);
        } catch (\Exception $e) {
            throw new \Exception('Can not parse image');
        }

        $fileName = sha1(rand().microtime()) . '.jpg';
        $fileSaved = Storage::disk('avatars')->put($fileName, $image);

        if ($fileSaved) {
            return $fileName;
        }

        throw new \Exception('Can not store avatar file');
    }
}
