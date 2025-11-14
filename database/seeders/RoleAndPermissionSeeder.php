<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\password;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        Role::firstOrCreate(['name' => 'citizen']);
        //create roles
       // $adminRole = Role::create(['name' => 'admin']);
      //  $pationRole = Role::create(['name' => 'pation']);
       // $citizenRole = Role::create(['name' => 'citizen']);
     //   $secertarieRole = Role::create(['name' => 'secrtary']);


        //define permission
//        $permissions = [
//            'add appointment','updateappointment','deleteappointment','indexappointment'
//        ];
//        foreach ($permissions as $permissionName){
//            Permission::findOrCreate($permissionName,'web');
//        }

//        ////
//        $adminRole->syncPermissions($permissions);
//        $pationRole->givePermissionTo(['add appointment','updateappointment']);
//
//        /////create admin and
//        $adminUser  = User::factory()->create([
//          'name'=>'Admin User',
//            'email'=>'rayaaghawani@gmail.com',
//          ' password'=>bcrypt('password'),
//
//
//        ]);
//        $adminUser->assignRole($adminRole);
//        //assign permission with the rolr to the permission
//        $permissions = $adminRole->permissions()->pluck('name')->toArray();
//        $adminUser->givePermissionTo($permissions);
//


    }
}

