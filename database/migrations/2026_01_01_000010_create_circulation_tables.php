<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->index('idx_loans_member_id');
            $table->unsignedBigInteger('physical_item_id')->index('idx_loans_physical_item_id');
            $table->dateTime('loan_date')->index('idx_loans_loan_date');
            $table->dateTime('due_date')->index('idx_loans_due_date');
            $table->dateTime('returned_at')->nullable();
            $table->string('loan_status', 30)->default('active')->index('idx_loans_loan_status');
            $table->unsignedBigInteger('loaned_by')->nullable()->index('idx_loans_loaned_by');
            $table->unsignedBigInteger('closed_by')->nullable()->index('idx_loans_closed_by');
            $table->text('notes')->nullable();
            // Generated column handled via raw SQL below
            $table->timestamps();

            $table->foreign('member_id', 'fk_loans_member')
                ->references('id')->on('members')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('physical_item_id', 'fk_loans_physical_item')
                ->references('id')->on('physical_items')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('loaned_by', 'fk_loans_loaned_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('closed_by', 'fk_loans_closed_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        // Add generated column for unique active loan per item (VIRTUAL for MySQL compatibility)
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE `loans` ADD COLUMN `active_physical_item_id` BIGINT UNSIGNED
            GENERATED ALWAYS AS (CASE WHEN `loan_status` = 'active' THEN `physical_item_id` ELSE NULL END) VIRTUAL
        ");
        // Note: MySQL requires a BTREE index on virtual columns for unique constraint
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE `loans` ADD UNIQUE KEY `uq_loans_active_physical_item_id` (`active_physical_item_id`)
        ");

        Schema::create('loan_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->index('idx_loan_renewals_loan_id');
            $table->dateTime('old_due_date');
            $table->dateTime('new_due_date');
            $table->unsignedBigInteger('renewed_by')->nullable()->index('idx_loan_renewals_renewed_by');
            $table->text('notes')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('loan_id', 'fk_loan_renewals_loan')
                ->references('id')->on('loans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('renewed_by', 'fk_loan_renewals_renewed_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('return_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->unique('uq_return_transactions_loan_id');
            $table->unsignedBigInteger('physical_item_id')->index('idx_return_transactions_physical_item_id');
            $table->dateTime('returned_at')->index('idx_return_transactions_returned_at');
            $table->unsignedBigInteger('returned_by')->nullable()->index('idx_return_transactions_returned_by');
            $table->unsignedBigInteger('returned_condition_id')->nullable()->index('idx_return_transactions_returned_condition_id');
            $table->integer('late_days')->default(0);
            $table->decimal('fine_amount', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('loan_id', 'fk_return_transactions_loan')
                ->references('id')->on('loans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('physical_item_id', 'fk_return_transactions_physical_item')
                ->references('id')->on('physical_items')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('returned_by', 'fk_return_transactions_returned_by')
                ->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('returned_condition_id', 'fk_return_transactions_returned_condition')
                ->references('id')->on('item_conditions')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id')->unique('uq_fines_loan_id');
            $table->unsignedBigInteger('member_id')->index('idx_fines_member_id');
            $table->string('fine_type', 30)->default('overdue')->index('idx_fines_fine_type');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->integer('late_days')->default(0);
            $table->string('status', 30)->default('outstanding')->index('idx_fines_status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('loan_id', 'fk_fines_loan')
                ->references('id')->on('loans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('member_id', 'fk_fines_member')
                ->references('id')->on('members')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
        Schema::dropIfExists('return_transactions');
        Schema::dropIfExists('loan_renewals');
        Schema::dropIfExists('loans');
    }
};
