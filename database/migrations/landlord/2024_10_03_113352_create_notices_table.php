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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('notice_number')->required()->unique();
            $table->string('title', 1000)->required();
            $table->string('contents', 4000)->nullable();
            $table->string('detail_url', 200)->nullable();
            $table->string('registration_name', 60)->nullable();
            $table->string('registration_date', 14)->nullable();


            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
