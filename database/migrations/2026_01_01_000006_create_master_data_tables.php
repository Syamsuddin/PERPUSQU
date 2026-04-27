<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->index('idx_authors_name');
            $table->string('normalized_name', 200)->nullable()->index('idx_authors_normalized_name');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index('idx_authors_is_active');
            $table->timestamps();
        });

        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->index('idx_publishers_name');
            $table->string('city', 150)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index('idx_publishers_is_active');
            $table->timestamps();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique('uq_languages_code');
            $table->string('name', 100)->unique('uq_languages_name');
            $table->boolean('is_active')->default(true)->index('idx_languages_is_active');
            $table->timestamps();
        });

        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index('idx_classifications_parent_id');
            $table->string('code', 50)->unique('uq_classifications_code');
            $table->string('name', 200);
            $table->boolean('is_active')->default(true)->index('idx_classifications_is_active');
            $table->timestamps();
            $table->foreign('parent_id', 'fk_classifications_parent')
                ->references('id')->on('classifications')
                ->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->index('idx_subjects_name');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index('idx_subjects_is_active');
            $table->timestamps();
        });

        Schema::create('collection_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_collection_types_code');
            $table->string('name', 150)->unique('uq_collection_types_name');
            $table->boolean('is_active')->default(true)->index('idx_collection_types_is_active');
            $table->timestamps();
        });

        Schema::create('rack_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_rack_locations_code');
            $table->string('name', 150)->index('idx_rack_locations_name');
            $table->string('floor', 50)->nullable();
            $table->string('room', 100)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index('idx_rack_locations_is_active');
            $table->timestamps();
        });

        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_faculties_code');
            $table->string('name', 150)->unique('uq_faculties_name');
            $table->boolean('is_active')->default(true)->index('idx_faculties_is_active');
            $table->timestamps();
        });

        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faculty_id')->index('idx_study_programs_faculty_id');
            $table->string('code', 50)->unique('uq_study_programs_code');
            $table->string('name', 150);
            $table->boolean('is_active')->default(true)->index('idx_study_programs_is_active');
            $table->timestamps();
            $table->foreign('faculty_id', 'fk_study_programs_faculty')
                ->references('id')->on('faculties')
                ->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::create('item_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique('uq_item_conditions_code');
            $table->string('name', 150)->unique('uq_item_conditions_name');
            $table->integer('severity_level')->default(1);
            $table->boolean('is_active')->default(true)->index('idx_item_conditions_is_active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_conditions');
        Schema::dropIfExists('study_programs');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('rack_locations');
        Schema::dropIfExists('collection_types');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('publishers');
        Schema::dropIfExists('authors');
    }
};
