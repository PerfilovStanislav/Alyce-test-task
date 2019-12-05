<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding of Admin
        $user = \App\Models\User::create(['name' => 'Stanislav']);
        $role = \App\Models\Role::create(['name' => 'Admin']);
        $user->roles()->save($role)->abilities()->createMany([
            ['name' => 'AddUser'],
            ['name' => 'EditUser'],
            ['name' => 'DeleteUser'],

            ['name' => 'AddRole'],
            ['name' => 'EditRole'],
            ['name' => 'DeleteRole'],

            ['name' => 'AddAbbility'],
            ['name' => 'EditAbbility'],
            ['name' => 'DeleteAbbility'],
        ]);

        // Adding of Editor
        $user = \App\Models\User::create(['name' => 'Andrey']);
        $role = \App\Models\Role::create(['name' => 'Editor']);
        $user->roles()->save($role)->abilities()->saveMany(\App\Models\Ability::find([2, 5, 8]));

        //Adding of User
        $user = \App\Models\User::create(['name' => 'Alex']);
        $role = \App\Models\Role::create(['name' => 'User']);
        $user->roles()->save($role);
    }
}
