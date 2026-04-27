<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('physical_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bibliographic_record_id')->index('idx_physical_items_record_id');
            $table->unsignedBigInteger('rack_location_id')->nullable()->index('idx_physical_items_rack_location_id');
            $table->unsignedBigInteger('item_condition_id')->nullable()->index('idx_physical_items_item_condition_id');
            $table->string('barcode', 100)->unique('uq_physical_items_barcode');
            $table->string('inventory_code', 100)->nullable()->unique('uq_physical_items_inventory_code');
            $table->date('acquisition_date')->nullable()->index('idx_physical_items_acquisition_date');
            $table->string('item_status', 30)->default('available')->index('idx_physical_items_item_status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bibliographic_record_id', 'fk_physical_items_record')
                ->references('id')->on('bibliographic_records')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('rack_location_id', 'fk_physical_items_rack_location')
                ->references('id')->on('rack_locations')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('item_condition_id', 'fk_physical_items_item_condition')
                ->references('id')->on('item_conditions')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('physical_item_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('physical_item_id')->index('idx_physical_item_status_histories_item_id');
            $table->string('old_status', 30)->nullable();
            $table->string('new_status', 30);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable()->index('idx_physical_item_status_histories_changed_by');
            $table->dateTime('created_at')->useCurrent()->index('idx_physical_item_status_histories_created_at');

            $table->foreign('physical_item_id', 'fk_item_status_histories_item')
                ->references('id')->on('physical_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('changed_by', 'fk_item_status_histories_changed_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('physical_item_status_histories');
        Schema::dropIfExists('physical_items');
    }
};
