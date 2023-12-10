<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportInitialData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $sql = file_get_contents(database_path('../import/database.sql'));
        DB::unprepared($sql);
    }
}
