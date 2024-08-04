<?php

use App\Models\Parameter;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_images', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('image_type');
            $table->foreignIdFor(Parameter::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_images');
    }
};
