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

        // Schema::create('branch_users', function (Blueprint $table) {
            // $table->id();
            // $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->required(); // cascade when parent is deleted

            // $table->string('username',60)->required()->unique();
            // $table->string('contact',20)->nullable();
            // $table->string('password',255)->required() ;// should be hashed
            // $table->string('address',200)->nullable();
            // $table->string('authority_code',100)->nullable();
            // $table->string('remark',2000)->nullable();
            // $table->string('used_unused', 1)->default("Y");  // Y or N
            // $table->timestamp('synced_at')->nullable(); 

            // $table->timestamps(6);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_users');
    }
};
