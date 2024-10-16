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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained("users");
            $table->foreignId("store_id")->constrained("stores");
            $table->string('number')->unique();
            $table->string("payment_method");
            $table->enum("status",["pending", "delivering", "processing","completed","cancelled","refuneded"])
                ->default("pending");
            $table->enum("payment_status",["pending", "failed", "paid"])
                ->default("pending");
                $table->float("shipping")->default(0);
                $table->float("tax")->default(0);
                $table->float("discouant")->default(0);
                $table->float("total")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
