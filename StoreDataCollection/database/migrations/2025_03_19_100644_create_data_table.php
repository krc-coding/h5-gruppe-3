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
        Schema::create('data', function (Blueprint $table) {
            $table->id();
            $table->integer('people');
            $table->integer('products_pr_person');
            $table->float('total_value');
            $table->json('product_categories');
            $table->integer('packages_received')->nullable();
            $table->integer('packages_delivered')->nullable();
            $table->unsignedBigInteger('devices_id');
            $table->timestamp('data_recorded_at');
            $table->foreign('devices_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data');
    }
};
