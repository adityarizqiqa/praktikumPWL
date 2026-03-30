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
        $duplicateSlugs = DB::table('posts')
            ->select('slug')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('slug');

        foreach ($duplicateSlugs as $slug) {
            $rows = DB::table('posts')
                ->where('slug', $slug)
                ->orderBy('id')
                ->get(['id']);

            $isFirst = true;
            foreach ($rows as $row) {
                if ($isFirst) {
                    $isFirst = false;
                    continue;
                }

                $candidate = $slug . '-' . $row->id . '-dup';
                $suffix = 1;
                while (DB::table('posts')->where('slug', $candidate)->exists()) {
                    $candidate = $slug . '-' . $row->id . '-dup-' . $suffix;
                    $suffix++;
                }

                DB::table('posts')->where('id', $row->id)->update(['slug' => $candidate]);
            }
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
