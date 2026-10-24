<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            // Telepon unik supaya form masuk pelanggan bisa mencari customer
            // lama berdasarkan telepon tanpa membuat duplikat.
            $table->string('phone', 30)->unique();
            $table->string('email', 150)->nullable()->unique();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
