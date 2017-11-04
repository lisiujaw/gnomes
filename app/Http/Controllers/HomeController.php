<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\GnomeCreateRequets;
use App\Http\Requests\GnomeEditRequets;
use Illuminate\Http\Request;
use App\Models\Gnome;
use File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the gnomes
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) : \Illuminate\View\View
    {
        $gnomes = $request->user()
            ->getGnomes();

        return view('home', [
            'gnomes' => $gnomes,
        ]);
    }

    /**
     * View gnome data and show edit form
     *
     * @return \Illuminate\View\View
     */
    public function view(Gnome $gnome, Request $request) : \Illuminate\View\View
    {
        if (empty($gnome)) {
            throw new NotFoundHttpException('Gnome not found!');
        }

        return view('edit', [
            'gnome' => $gnome,
        ]);
    }

    /**
     * Create new gnome
     *
     * @param  GnomeCreateRequets $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(GnomeCreateRequets $request) : \Illuminate\Http\RedirectResponse
    {
        $gnome = new Gnome([
            'name' => $request->input('name'),
            'strength' => $request->input('strength'),
            'age' => $request->input('age'),
        ]);

        $file = $request->file('avatar');
        $extension = strtolower(File::extension($file->getClientOriginalName()));
        $fileName = sha1(rand().microtime()) . '.' . $extension;

        $fileSaved = $file->storeAs(
            '', $fileName, ['disk' => 'avatars']
        );

        if (! $fileSaved) {
            throw new \Exception('Can not save avatar file');
        }

        $gnome->setAvatarFileName($fileName);
        $gnome->setUser($request->user());

        if ($gnome->save()) {
            $request->session()->flash('status', 'Your new gnome was saved!');

            return redirect(route('gnome_edit', $gnome));
        }

        return redirect(route('gnome_create'));
    }

    /**
     * Update gnome data
     *
     * @param  Gnome            $gnome
     * @param  GnomeEditRequets $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Gnome $gnome, GnomeEditRequets $request) : \Illuminate\Http\RedirectResponse
    {
        if (empty($gnome)) {
            throw new NotFoundHttpException('Gnome not found!');
        }

        $gnomeData = [
            'name' => $request->input('name'),
            'strength' => $request->input('strength'),
            'age' => $request->input('age'),
        ];

        if ($request->has('avatar')) {
            $file = $request->file('avatar');
            $extension = strtolower(File::extension($file->getClientOriginalName()));
            $newFileName = sha1(rand().microtime()) . '.' . $extension;

            $saved = $file->storeAs(
                '', $newFileName, ['disk' => 'avatars']
            );

            if ($saved) {
                $gnomeData['avatar_file'] = $newFileName;
            }
        }

        $updated = $gnome->update($gnomeData);

        $request->session()->flash(
            'status',
            $updated
                ? 'Gnome was updated!'
                : 'Gnome was NOT updated!'
        );

        return redirect(route('gnome_edit', $gnome));
    }

    /**
     * Delete gnome and redirect to home page
     *
     * @param  Gnome   $gnome
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Gnome $gnome, Request $request) : \Illuminate\Http\RedirectResponse
    {
        if (empty($gnome)) {
            throw new NotFoundHttpException('Gnome not found!');
        }

        $request->session()->flash(
            'status',
            $gnome->delete()
                ? 'Gnome was deleted!'
                : 'Gnome was NOT deleted!'
        );

        return redirect('home');
    }
}
