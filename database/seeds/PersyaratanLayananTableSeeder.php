<?php

use Illuminate\Database\Seeder;

class PersyaratanLayananTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('persyaratan_layanan')->insert([
        	[
        		'id' => 1,
        		'id_layanan' => 'SKD',
        		'id_persyaratan' => 1,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 2,
        		'id_layanan' => 'SKD',
        		'id_persyaratan' => 2,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 3,
        		'id_layanan' => 'SKD',
        		'id_persyaratan' => 3,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 4,
        		'id_layanan' => 'SKKB',
        		'id_persyaratan' => 1,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 5,
        		'id_layanan' => 'SKKB',
        		'id_persyaratan' => 2,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 6,
        		'id_layanan' => 'SKKB',
        		'id_persyaratan' => 3,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 7,
        		'id_layanan' => 'SKSG',
        		'id_persyaratan' => 1,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 8,
        		'id_layanan' => 'SKSG',
        		'id_persyaratan' => 2,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 9,
        		'id_layanan' => 'SKSG',
        		'id_persyaratan' => 3,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 10,
        		'id_layanan' => 'SKTM-KES',
        		'id_persyaratan' => 1,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 11,
        		'id_layanan' => 'SKTM-KES',
        		'id_persyaratan' => 2,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 12,
        		'id_layanan' => 'SKTM-KES',
        		'id_persyaratan' => 3,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 13,
        		'id_layanan' => 'SKTM-PEN',
        		'id_persyaratan' => 1,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 14,
        		'id_layanan' => 'SKTM-PEN',
        		'id_persyaratan' => 2,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        	[
        		'id' => 15,
        		'id_layanan' => 'SKTM-PEN',
        		'id_persyaratan' => 3,
        		'created_at' => date('Y-m-d H:i:s'),
        		'updated_at' => date('Y-m-d H:i:s'),
        	],
        ]);
    }
}
