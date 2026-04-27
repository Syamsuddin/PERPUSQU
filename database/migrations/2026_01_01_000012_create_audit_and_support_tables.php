<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index('idx_activity_logs_user_id');
            $table->string('action', 100)->index('idx_activity_logs_action');
            $table->string('module_name', 100)->index('idx_activity_logs_module_name');
            $table->string('subject_type', 150)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->dateTime('created_at')->useCurrent()->index('idx_activity_logs_created_at');

            $table->index(['subject_type', 'subject_id'], 'idx_activity_logs_subject');

            $table->foreign('user_id', 'fk_activity_logs_user')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('report_export_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exported_by')->nullable()->index('idx_report_export_histories_exported_by');
            $table->string('report_type', 100);
            $table->string('format', 30);
            $table->string('file_path', 500)->nullable();
            $table->string('status', 30)->default('pending')->index('idx_report_export_histories_status');
            $table->json('parameters')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('exported_by', 'fk_report_export_histories_exported_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('queue_monitor_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('queue_name', 100)->index('idx_queue_monitor_queue_name');
            $table->integer('pending_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->integer('processing_count')->default(0);
            $table->dateTime('snapshot_at')->index('idx_queue_monitor_snapshot_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_monitor_snapshots');
        Schema::dropIfExists('report_export_histories');
        Schema::dropIfExists('activity_logs');
    }
};
