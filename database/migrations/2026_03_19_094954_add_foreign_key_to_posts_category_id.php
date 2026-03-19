<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE posts MODIFY category_id BIGINT UNSIGNED NOT NULL');

        Schema::table('posts', function (Blueprint $table) {
            $table
                ->foreign('category_id', 'posts_category_id_foreign')
                ->references('id')
                ->on('categories')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('posts_category_id_foreign');
        });

        DB::statement('ALTER TABLE posts MODIFY category_id INT NOT NULL');
    }
};
