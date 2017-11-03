<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('name', 'devel')->first();

        if ($user != null) {
            return;
        }

        User::create([
            'name' => 'devel',
            'email' => 'devel@devel.devel',
            'password' => bcrypt('devel'),
        ]);
    }
}
