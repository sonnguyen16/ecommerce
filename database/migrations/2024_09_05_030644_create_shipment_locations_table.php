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
        Schema::create('shipment_locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_detail_id')->unsigned();
            $table->string('address')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('order_detail_id')
                ->references('id')
                ->on('order_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_locations');
    }
};
