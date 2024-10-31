<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = SimpleExcelReader::create(__DIR__.'/data/Country.xlsx')->getRows();

        $rows->each(function(array $row) {

            $data['id'] = $row['id'];
            $data['name'] = strtoupper($row['name']);
            $data['code'] = strtoupper($row['code']);
            $data['status'] = 'active';

            Country::create($data);

        });
    }
}
