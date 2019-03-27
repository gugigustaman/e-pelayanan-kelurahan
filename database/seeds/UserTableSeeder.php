<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('user')->insert([
		    [
		    	'id_user' => 1,
		    	'nama_user' => 'admin',
		    	'password' => bcrypt('rahasia'),
		    	'nama_petugas' => 'Administrator',
		    	'jabatan' => 'Administrator',
		    	'alamat' => 'Antapani Tengah',
		    	'lvl_user' => 1,
		    	'created_at' => date('Y-m-d H:i:s'),
		    	'updated_at' => date('Y-m-d H:i:s'),
		    ]
	    ]);
    }
}
