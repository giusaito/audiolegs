<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;
use App\Laravue\Models\userProfile;
use App\Laravue\Models\Plan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StateTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(CollegeTableSeeder::class);
        $admin = User::create([
            'universidade_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Admin',
            'email' => 'admin@admin.com.br',
            'password' => Hash::make('123456'),
        ]);
        $manager = User::create([
            'universidade_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Manager',
            'email' => 'manager@admin.com.br',
            'password' => Hash::make('123456'),
        ]);
        $editor = User::create([
            'universidade_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Editor',
            'email' => 'editor@admin.com.br',
            'password' => Hash::make('123456'),
        ]);
        $user = User::create([
            'universidade_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'User',
            'email' => 'user@admin.com.br',
            'password' => Hash::make('123456'),
        ]);
        $visitor = User::create([
            'universidade_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Visitor',
            'email' => 'visitor@admin.com.br',
            'password' => Hash::make('123456'),
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);

        $admin->userProfile()->create([
             'user_id' => $admin->id,
             'path' => 'https://picsum.photos/',
             'photo' => '200'
        ]);
        $editor->userProfile()->create([
             'user_id' => $editor->id,
             'path' => 'https://picsum.photos/',
             'photo' => '200'
        ]);
        factory(Plan::class, 5)->create();
        $this->call(UsersTableSeeder::class);
    }
}
