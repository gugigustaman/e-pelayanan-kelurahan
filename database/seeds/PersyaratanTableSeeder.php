<?php

use Illuminate\Database\Seeder;

class PersyaratanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('persyaratan')->insert([
			[
				'id_persyaratan' => 1,
				'jenis_persyaratan' => 'Foto/Scan KTP',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_persyaratan' => 2,
				'jenis_persyaratan' => 'Foto/Scan KK',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_persyaratan' => 3,
				'jenis_persyaratan' => 'Surat Pengantar RT/RW',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]
		]);
    }
}
