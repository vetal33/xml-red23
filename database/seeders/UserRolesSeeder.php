<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::ROLES_LABEL as $roleKey => $role) {
            $roleExists = DB::table('roles')->where('name', $roleKey)->get();

            if (!count($roleExists)) {
                Role::create(['name' => $roleKey]);
            }
        }
    }
}
