<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $path = database_path('db.sql');
        $sql = file_get_contents($path);

        $sql = str_replace(['USE ', 'LOCK TABLES', 'UNLOCK TABLES'], '--', $sql);

        DB::unprepared($sql);
    }
}
