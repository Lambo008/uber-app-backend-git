<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('admin')->insert([
            'username'=>'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'ADMIN',
        ]);
    }
}
