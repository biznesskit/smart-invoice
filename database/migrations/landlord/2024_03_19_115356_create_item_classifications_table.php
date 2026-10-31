<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('name');
            $table->text('description')->nullable();
            $table->string('tax_type_code')->nullable();
            $table->string('tax_class_level')->nullable();
            $table->string('major_target')->nullable();
            $table->string('is_major_target')->nullable();
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_classifications');
    }
};
