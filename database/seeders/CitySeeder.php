<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = array(
			array('code' => 'KPJ', 'name' => 'Kepanjen'),
			array('code' => 'GDL', 'name' => 'Gondanglegi'),
			array('code' => 'TRN', 'name' => 'Turen'),
			array('code' => 'PGL', 'name' => 'Pagelaran'),
			array('code' => 'PGK', 'name' => 'Pagak'),
			array('code' => 'KRM', 'name' => 'Kromengan'),
			array('code' => 'NGJ', 'name' => 'Ngajum'),
		);

		DB::table('cities')->insert($cities);
    }
}
