<?php

use Illuminate\Database\Seeder;

class LayananTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('layanan')->insert([
			[
				'id_layanan' => 'SKD',
				'jenis_layanan' => 'Surat Keterangan Domisili',
				'template_path' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_layanan' => 'SKKB',
				'jenis_layanan' => 'Surat Keterangan Kelakuan Baik',
				'template_path' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_layanan' => 'SKSG',
				'jenis_layanan' => 'Surat Keterangan Serbaguna',
				'template_path' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_layanan' => 'SKTM-KES',
				'jenis_layanan' => 'Surat Keterangan Tidak Mampu (Kesehatan)',
				'template_path' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			],
			[
				'id_layanan' => 'SKTM-PEN',
				'jenis_layanan' => 'Surat Keterangan Tidak Mampu (Pendidikan)',
				'template_path' => '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]
		]);
    }
}
