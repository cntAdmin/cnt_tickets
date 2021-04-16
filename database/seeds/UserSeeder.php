<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (AppEnv::environment('local')) {

        factory(App\Models\User::class, 100)->create()->each(function($user) {
            $user->customer()->associate(App\Models\Customer::inRandomOrder()->first());
            $user->syncRoles(Spatie\Permission\Models\Role::inRandomOrder()->first());
            $user->save();
        });
    }
        $user = App\Models\User::create([
            'name' => 'Nach',
            'username' => 'channito',
            'email' => 'nacho@costanetworks.es',
            'password' => Hash::make('A12345bc'),
            'email_verified_at' => now()->toDateTimeString(),
            'is_active' => true
        ]);
        $user->syncRoles(1);
    }
}
