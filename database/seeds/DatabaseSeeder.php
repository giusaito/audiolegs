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
        $this->call(UniversityTableSeeder::class);
        $admin = User::create([
            'university_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Admin',
            'email' => 'admin@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);
        $manager = User::create([
            'university_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Manager',
            'email' => 'manager@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);
        $editor = User::create([
            'university_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Editor',
            'email' => 'editor@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);
        $user = User::create([
            'university_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'User',
            'email' => 'user@laravue.dev',
            'password' => Hash::make('laravue'),
        ]);
        $visitor = User::create([
            'university_id' => rand(1,5),
            'state_id' => 18,
            'city_id' => 3994,
            'name' => 'Visitor',
            'email' => 'visitor@laravue.dev',
            'password' => Hash::make('laravue'),
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
