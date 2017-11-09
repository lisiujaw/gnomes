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
    public function user(Request $request) : User
    {
        return $request->user();
    }

    /**
     * Return Collection of gnomes
     *
     * @param  Request $request
     * @return Collection
     */
    public function listGnomes(Request $request) : Collection
    {
        return app('GnomeService')
            ->getUserGnomes($request->user());
    }

    /**
     * Return gnome
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return array
     */
    public function getGnome(Request $request, int $gnomeId) : array
    {
        try {
            return [
                'status' => true,
                'gnome' => app('GnomeService')->getGnomeById($gnomeId),
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
            ];
        }
    }

    /**
     * Retrun status of gnome deletion
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return array
     */
    public function deleteGnome(Request $request, int $gnomeId) : array
    {
        try {
            return [
                'status' => app('GnomeService')->deleteGnomeById($gnomeId)
            ];
        } catch (\Exception $e) {
            return ['status' => false];
        }
    }

    /**
     * Create new gnome
     *
     * @param  Request $request
     * @return array
     */
    public function createGnome(Request $request) : array
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'age'       => 'required|integer|min:0|max:100',
            'strength'  => 'required|integer|min:0|max:100',
            'avatar'    => 'required|string',
        ]);

        if ($validation->fails()) {
            return [
                'status' => false,
                'error' => 'Validation error',
                'validation_errors' => $validation->errors()->messages()
            ];
        }

        try {
            $gnome = app('GnomeService')->createGnome([
                    'name' => $request->input('name'),
                    'strength' => $request->input('strength'),
                    'age' => $request->input('age'),
                ],
                $request->input('avatar'),
                $request->user()
            );
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }

        if (is_a($gnome, Gnome::class)) {
            return [
                'status' => true,
                'gnome' => $gnome,
            ];
        }

        return [
            'status' => false,
            'error' => 'Can not create gnome',
        ];
    }

    /**
     * Edit gnome
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return array
     */
    public function editGnome(Request $request, int $gnomeId) : array
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'max:255',
            'age'       => 'integer|min:0|max:100',
            'strength'  => 'integer|min:0|max:100',
            'avatar'    => 'string',
        ]);

        if ($validation->fails()) {
            return [
                'status' => false,
                'error' => 'Validation error',
                'validation_errors' => $validation->errors()->messages()
            ];
        }

        $gnomeData = $request->all();
        unset($gnomeData['avatar']);

        try {
            $updated = app('GnomeService')->updateGnomeById(
                $gnomeId,
                $gnomeData,
                $request->has('avatar') ? $request->input('avatar') : null
            );
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }

        if ($updated) {
            return [
                'status' => true,
                'gnome' => app('GnomeService')->getGnomeById($gnomeId)
            ];
        }

        return [
            'status' => false,
            'error' => 'Can not update gnome',
        ];
    }
}
