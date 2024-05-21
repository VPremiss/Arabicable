<?php

declare(strict_types = 1);

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
        Schema::create('arabic_plurals', function (Blueprint $table) {
            $table->id();

            $table->arabicString('singular', length: 20);

            $table->arabicString('plural', length: 25);

            $table->unique(['singular', 'plural']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arabic_plurals');
    }
};
