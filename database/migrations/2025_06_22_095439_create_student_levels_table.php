<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_levels', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('class_level_id');
            $table->unsignedBigInteger('sub_class_level_id');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_graduate')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('school_year_id')->references('id')->on('school_years')->restrictOnDelete();
            $table->foreign('class_level_id')->references('id')->on('class_levels')->restrictOnDelete();
            $table->foreign('sub_class_level_id')->references('id')->on('sub_class_levels')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_levels');
    }
};
