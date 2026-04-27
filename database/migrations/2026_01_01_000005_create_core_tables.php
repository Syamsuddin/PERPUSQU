<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institution_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name', 255);
            $table->string('library_name', 255);
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->longText('about_text')->nullable();
            $table->timestamps();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 150)->unique('uq_system_settings_key');
            $table->longText('value')->nullable();
            $table->string('type', 30)->default('string');
            $table->string('group_name', 100)->default('general')->index('idx_system_settings_group_name');
            $table->boolean('is_public')->default(false)->index('idx_system_settings_is_public');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('institution_profiles');
    }
};
