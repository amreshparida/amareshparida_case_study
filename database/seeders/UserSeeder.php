<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

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
        //Generating a sample user for actions like login
        $user = new User;
        $user->name = 'Amaresh Parida';
        $user->email = 'amresh.parida10000@gmail.com';
        //Using Hash for making encrypted password
        $user->password = Hash::make('Default@123');
        $user->save();
    }
}
