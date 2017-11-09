<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\GnomeNotFound;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function user(Request $request) : JsonResponse
    {
        return $this->response($request->user()->toArray(), 200);
    }

    /**
     * Return Collection of gnomes
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function listGnomes(Request $request) : JsonResponse
    {
        return $this->response(
            app('GnomeService')->getUserGnomes($request->user())->toArray()
        , 200);
    }

    /**
     * Return gnome
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return JsonResponse
     */
    public function getGnome(Request $request, int $gnomeId) : JsonResponse
    {
        try {
            return $this->response(
                [
                    'status' => true,
                    'gnome' => app('GnomeService')->getGnomeById($gnomeId),
                ], 200
            );
        } catch (\Exception $e) {
            return $this->response([
                'status' => false,
            ], 404);
        }
    }

    /**
     * Retrun status of gnome deletion
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return JsonResponse
     */
    public function deleteGnome(Request $request, int $gnomeId) : JsonResponse
    {
        try {
            return $this->response([
                'status' => app('GnomeService')->deleteGnomeById($gnomeId)
            ], 200);
        } catch (GnomeNotFound $e) {
            return $this->response([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return $this->response([
                'status' => false,
            ], 500);
        }
    }

    /**
     * Create new gnome
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function createGnome(Request $request) : JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'required|max:255',
            'age'       => 'required|integer|min:0|max:100',
            'strength'  => 'required|integer|min:0|max:100',
            'avatar'    => 'required|string',
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'error' => 'Validation error',
                'validation_errors' => $validation->errors()->messages(),
            ], 500);
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
            return $this->response([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

        if (is_a($gnome, Gnome::class)) {
            return $this->response([
                'status' => true,
                'gnome' => $gnome,
            ], 200);
        }

        return $this->response([
            'status' => false,
            'error' => 'Can not create gnome',
        ], 500);
    }

    /**
     * Edit gnome
     *
     * @param  Request $request
     * @param  int     $gnomeId
     * @return JsonResponse
     */
    public function editGnome(Request $request, int $gnomeId) : JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'name'      => 'max:255',
            'age'       => 'integer|min:0|max:100',
            'strength'  => 'integer|min:0|max:100',
            'avatar'    => 'string',
        ]);

        if ($validation->fails()) {
            return $this->response([
                'status' => false,
                'error' => 'Validation error',
                'validation_errors' => $validation->errors()->messages(),
            ], 500);
        }

        $gnomeData = $request->all();
        unset($gnomeData['avatar']);

        try {
            $updated = app('GnomeService')->updateGnomeById(
                $gnomeId,
                $gnomeData,
                $request->has('avatar') ? $request->input('avatar') : null
            );
        } catch (GnomeNotFound $e) {
            return $this->response([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return $this->response([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

        if ($updated) {
            return $this->response([
                'status' => true,
                'gnome' => app('GnomeService')->getGnomeById($gnomeId),
            ], 200);
        }

        return $this->response([
            'status' => false,
            'error' => 'Can not update gnome',
        ], 500);
    }

    /**
     * Prepare JsonResponse object
     *
     * @param  array $data
     * @param  int   $status
     * @return JsonResponse
     */
    private function response(array $data, int $status = 200) : JsonResponse
    {
        return response()
            ->json($data, $status);
    }
}
