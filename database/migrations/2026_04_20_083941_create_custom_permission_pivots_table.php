<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Spatie permission tables are handled by vendor publish.
        // This migration creates the custom pivot tables per 14_SCHEMA.sql.

        // user_roles pivot (extends spatie model_has_roles)
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->dateTime('assigned_at')->useCurrent();
                $table->primary(['user_id', 'role_id']);
                $table->index('role_id', 'idx_user_roles_role_id');
                $table->foreign('user_id', 'fk_user_roles_user')
                    ->references('id')->on('users')
                    ->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreign('role_id', 'fk_user_roles_role')
                    ->references('id')->on('roles')
                    ->cascadeOnUpdate()->cascadeOnDelete();
            });
        }

        // role_permissions pivot (extends spatie role_has_permissions)
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->dateTime('assigned_at')->useCurrent();
                $table->primary(['role_id', 'permission_id']);
                $table->index('permission_id', 'idx_role_permissions_permission_id');
                $table->foreign('role_id', 'fk_role_permissions_role')
                    ->references('id')->on('roles')
                    ->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreign('permission_id', 'fk_role_permissions_permission')
                    ->references('id')->on('permissions')
                    ->cascadeOnUpdate()->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
    }
};
