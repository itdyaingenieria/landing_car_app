<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Load data of CSV
        $csvPath = database_path('seeders/data/departments.csv');
        if (file_exists($csvPath)) {
            $departments = array_map('str_getcsv', file($csvPath));
            foreach ($departments as $row) {
                \Illuminate\Support\Facades\DB::table('departments')->insert([
                    'name' => $row[0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
