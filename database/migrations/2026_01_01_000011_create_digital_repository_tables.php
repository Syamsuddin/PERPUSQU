<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bibliographic_record_id')->index('idx_digital_assets_record_id');
            $table->string('asset_type', 40)->default('other')->index('idx_digital_assets_asset_type');
            $table->string('file_name', 255);
            $table->string('original_file_name', 255);
            $table->string('file_path', 500)->unique('uq_digital_assets_file_path');
            $table->string('mime_type', 150);
            $table->string('file_extension', 30);
            $table->unsignedBigInteger('file_size');
            $table->string('checksum', 128)->nullable()->index('idx_digital_assets_checksum');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('publication_status', 30)->default('draft')->index('idx_digital_assets_publication_status');
            $table->boolean('is_public')->default(false)->index('idx_digital_assets_is_public');
            $table->boolean('is_embargoed')->default(false)->index('idx_digital_assets_is_embargoed');
            $table->dateTime('embargo_until')->nullable();
            $table->string('ocr_status', 30)->default('not_requested')->index('idx_digital_assets_ocr_status');
            $table->string('index_status', 30)->default('pending')->index('idx_digital_assets_index_status');
            $table->dateTime('ocr_attempted_at')->nullable();
            $table->dateTime('last_indexed_at')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index('idx_digital_assets_uploaded_by');
            $table->dateTime('uploaded_at')->useCurrent()->index('idx_digital_assets_uploaded_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bibliographic_record_id', 'fk_digital_assets_record')
                ->references('id')->on('bibliographic_records')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('uploaded_by', 'fk_digital_assets_uploaded_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('digital_asset_access_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('digital_asset_id')->index('idx_digital_asset_access_rules_asset_id');
            $table->string('access_scope', 30)->default('public')->index('idx_digital_asset_access_rules_scope');
            $table->string('role_name', 100)->nullable()->index('idx_digital_asset_access_rules_role_name');
            $table->string('member_type', 30)->nullable()->index('idx_digital_asset_access_rules_member_type');
            $table->boolean('allow_preview')->default(true);
            $table->boolean('allow_download')->default(false);
            $table->timestamps();

            $table->foreign('digital_asset_id', 'fk_digital_asset_access_rules_asset')
                ->references('id')->on('digital_assets')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('ocr_texts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('digital_asset_id')->unique('uq_ocr_texts_digital_asset_id');
            $table->string('source_type', 30)->default('ocr');
            $table->longText('extracted_text')->nullable();
            $table->string('extraction_status', 30)->default('pending')->index('idx_ocr_texts_extraction_status');
            $table->dateTime('extracted_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('digital_asset_id', 'fk_ocr_texts_asset')
                ->references('id')->on('digital_assets')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocr_texts');
        Schema::dropIfExists('digital_asset_access_rules');
        Schema::dropIfExists('digital_assets');
    }
};
