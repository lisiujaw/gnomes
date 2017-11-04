<?php

use Illuminate\Database\Seeder;
use App\Models\Gnome;
use App\Models\User;

class GnomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', 'devel')
            ->firstOrFail();

        for ($i=1; $i <= 10 ; $i++) {
            $gnome = factory(Gnome::class)
                ->make()
                ->setUser($user)
                ->save();
        }
    }
}
