<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255)->nullable(false);
            $table->text('description')->nullable();
            $table->boolean('completed')->default(false)->nullable(false);
            $table->foreignUuid('id_user')->constrained(
                table: 'users',
                column: 'id'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
