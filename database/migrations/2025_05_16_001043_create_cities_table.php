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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->timestamps();
        });

        // Load data of CSV for cities
        $csvPath = database_path('seeders/data/cities.csv');
        if (file_exists($csvPath)) {
            $cities = array_map('str_getcsv', file($csvPath));
            foreach ($cities as $row) {
                $department = \Illuminate\Support\Facades\DB::table('departments')->where('name', $row[1])->first();
                if ($department) {
                    \Illuminate\Support\Facades\DB::table('cities')->insert([
                        'name' => $row[0],
                        'department_id' => $department->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
