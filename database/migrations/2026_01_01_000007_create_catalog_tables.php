<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bibliographic_records', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique('uq_bibliographic_records_slug');
            $table->unsignedBigInteger('publisher_id')->nullable()->index('idx_bibliographic_records_publisher_id');
            $table->unsignedBigInteger('language_id')->nullable()->index('idx_bibliographic_records_language_id');
            $table->unsignedBigInteger('classification_id')->nullable()->index('idx_bibliographic_records_classification_id');
            $table->unsignedBigInteger('collection_type_id')->index('idx_bibliographic_records_collection_type_id');
            $table->year('publication_year')->nullable()->index('idx_bibliographic_records_publication_year');
            $table->string('isbn', 50)->nullable()->index('idx_bibliographic_records_isbn');
            $table->string('edition', 100)->nullable();
            $table->text('keywords')->nullable();
            $table->longText('abstract')->nullable();
            $table->string('cover_path', 255)->nullable();
            $table->string('publication_status', 30)->default('draft')->index('idx_bibliographic_records_publication_status');
            $table->boolean('is_public')->default(false)->index('idx_bibliographic_records_is_public');
            $table->json('metadata_json')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('idx_bibliographic_records_created_by');
            $table->unsignedBigInteger('updated_by')->nullable()->index('idx_bibliographic_records_updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['title', 'keywords', 'abstract'], 'ft_bibliographic_records_search');

            $table->foreign('publisher_id', 'fk_bibliographic_records_publisher')
                ->references('id')->on('publishers')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('language_id', 'fk_bibliographic_records_language')
                ->references('id')->on('languages')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('classification_id', 'fk_bibliographic_records_classification')
                ->references('id')->on('classifications')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('collection_type_id', 'fk_bibliographic_records_collection_type')
                ->references('id')->on('collection_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('created_by', 'fk_bibliographic_records_created_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('updated_by', 'fk_bibliographic_records_updated_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('bibliographic_record_authors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bibliographic_record_id');
            $table->unsignedBigInteger('author_id')->index('idx_bibliographic_record_authors_author_id');
            $table->integer('author_order')->default(1)->index('idx_bibliographic_record_authors_order');
            $table->string('role_label', 100)->nullable();
            $table->timestamps();

            $table->unique(['bibliographic_record_id', 'author_id'], 'uq_bibliographic_record_authors_unique');

            $table->foreign('bibliographic_record_id', 'fk_bibliographic_record_authors_record')
                ->references('id')->on('bibliographic_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('author_id', 'fk_bibliographic_record_authors_author')
                ->references('id')->on('authors')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::create('bibliographic_record_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bibliographic_record_id');
            $table->unsignedBigInteger('subject_id')->index('idx_bibliographic_record_subjects_subject_id');
            $table->timestamps();

            $table->unique(['bibliographic_record_id', 'subject_id'], 'uq_bibliographic_record_subjects_unique');

            $table->foreign('bibliographic_record_id', 'fk_bibliographic_record_subjects_record')
                ->references('id')->on('bibliographic_records')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('subject_id', 'fk_bibliographic_record_subjects_subject')
                ->references('id')->on('subjects')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bibliographic_record_subjects');
        Schema::dropIfExists('bibliographic_record_authors');
        Schema::dropIfExists('bibliographic_records');
    }
};
