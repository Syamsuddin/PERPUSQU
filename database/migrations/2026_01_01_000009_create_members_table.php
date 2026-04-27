<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number', 100)->unique('uq_members_member_number');
            $table->string('member_type', 30)->index('idx_members_member_type');
            $table->string('identity_number', 100)->nullable()->unique('uq_members_identity_number');
            $table->string('name', 200)->index('idx_members_name');
            $table->string('email', 150)->nullable()->index('idx_members_email');
            $table->string('phone', 50)->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable()->index('idx_members_faculty_id');
            $table->unsignedBigInteger('study_program_id')->nullable()->index('idx_members_study_program_id');
            $table->boolean('is_active')->default(true)->index('idx_members_is_active');
            $table->boolean('is_blocked')->default(false)->index('idx_members_is_blocked');
            $table->text('blocked_reason')->nullable();
            $table->dateTime('blocked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('faculty_id', 'fk_members_faculty')
                ->references('id')->on('faculties')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('study_program_id', 'fk_members_study_program')
                ->references('id')->on('study_programs')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
