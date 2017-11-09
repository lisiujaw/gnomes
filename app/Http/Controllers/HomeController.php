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
        $gnomes = app('GnomeService')
            ->getUserGnomes();

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
        try {
            $gnome = app('GnomeService')->createGnome(
                [
                    'name' => $request->input('name'),
                    'strength' => $request->input('strength'),
                    'age' => $request->input('age'),
                ],
                $request->file('avatar')
            );
        } catch (\Exception $e) {
            $request->session()
                ->flash('status', 'Can not create gnome! ' . $e->getMessage());

            return redirect(route('gnome_create'));
        }

        $request->session()
            ->flash('status', 'Your new gnome was saved!');

        return redirect(route('gnome_edit', $gnome));
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

        try {
            $updated = app('GnomeService')->updateGnome(
                $gnome,
                $gnomeData,
                $request->has('avatar') ? $request->file('avatar') : null
            );
        } catch (\Exception $e) {
            $request->session()
                ->flash('status', 'Can not edit gnome! ' . $e->getMessage());

            return redirect(route('home'));
        }

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

        $deleted = app('GnomeService')
            ->deleteGnome($gnome);

        $request->session()->flash(
            'status',
            $deleted
                ? 'Gnome was deleted!'
                : 'Gnome was NOT deleted!'
        );

        return redirect('home');
    }
}
