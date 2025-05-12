<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->integer('serial_number');
            $table->string('name');
            $table->enum('type', ['main_menu', 'sub_menu']);
            $table->string('main_menu')->nullable();
            $table->json('visibility')->nullable();
            $table->string('url')->default('#');
            $table->string('icon')->default('apps')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
