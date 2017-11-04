<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Gnome;
use App\Models\User;
use Validator;
use Storage;
use Image;

class ApiController extends Controller
{
    /**
     * Return logged User model
     *
     * @param  Request $request
     * @return User
     */
    public function user(Request $request)
    {
        return $request->user();
    }

    /**
     * Return Collection of gnomes
     *
     * @param  Request $request
     * @return Collection
     */
    public function listGnomes(Request $request)
    {
        return $request->user()
            ->getGnomes();
    }

    /**
     * Return gnome
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return Gnome|null
     */
    public function getGnome(Request $request, int $gnomeId)
    {
        return Gnome::find($gnomeId) ?? ['status' => false];
    }

    /**
     * Retrun status of gnome deletion
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return array
     */
    public function deleteGnome(Request $request, int $gnomeId)
    {
        $gnome = Gnome::find($gnomeId);

        if ($gnome == null) {
            return ['deleted' => false];
        }

        return ['deleted' => $gnome->delete()];
    }

    /**
     * Create new gnome
     *
     * @param  Request $request
     * @return Gnome|array
     */
    public function createGnome(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'age'       => 'required|integer|min:0|max:100',
            'strength'  => 'required|integer|min:0|max:100',
            'avatar'    => 'required|string',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors()->messages();

            return ['status' => false, 'errors' => $errors];
        }

        try {
            $image = Image::make($request->input('avatar'))
                ->encode('jpg', 100);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'errors' => [
                    'avatar' => [$e->getMessage()]
                ]
            ];
        }

        $imageFileName = sha1(rand().microtime()) . '.jpg';
        $fileSaved = Storage::disk('avatars')->put($imageFileName, $image);

        if ($fileSaved) {
            $gnome = new Gnome([
                'name' => $request->input('name'),
                'strength' => $request->input('strength'),
                'age' => $request->input('age'),
                'avatar_file' => $imageFileName,
            ]);

            $gnome->setUser($request->user());

            if ($gnome->save()) {
                $gnome->makeHidden('user');
            }
        }

        return ['status' => false];
    }


    public function editGnome(Request $request, int $gnomeId)
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'max:255',
            'age'       => 'integer|min:0|max:100',
            'strength'  => 'integer|min:0|max:100',
            'avatar'    => 'string',
        ]);

        if ($validation->fails()) {
            $errors = $validation->errors()->messages();

            return ['status' => false, 'errors' => $errors];
        }

        $gnome = Gnome::find($gnomeId);

        if ($gnome == null) {
            return ['status' => false];
        }

        if ($request->has('name')) {
            $gnome->setName($request->input('name'));
        }

        if ($request->has('age')) {
            $gnome->setAge($request->input('age'));
        }

        if ($request->has('strength')) {
            $gnome->setStrength($request->input('strength'));
        }

        if ($request->has('avatar')) {
            try {
                $image = Image::make($request->input('avatar'))
                    ->encode('jpg', 100);
            } catch (\Exception $e) {
                return [
                    'status' => false,
                    'errors' => [
                        'avatar' => [$e->getMessage()]
                    ]
                ];
            }

            $imageFileName = sha1(rand().microtime()) . '.jpg';
            $fileSaved = Storage::disk('avatars')->put($imageFileName, $image);

            if ($fileSaved) {
                $gnome->setAvatarFileName($imageFileName);
            }
        }

        if ($gnome->save()) {
            return $gnome->makeHidden('user');
        }

        return ['status' => false];
    }
}
