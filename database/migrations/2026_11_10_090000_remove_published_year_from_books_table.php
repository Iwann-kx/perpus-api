<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('books', 'published_year')) {
            return;
        }

        if (Schema::hasColumn('books', 'year')) {
            DB::table('books')
                ->where('year', 0)
                ->update(['year' => DB::raw('published_year')]);
        } else {
            Schema::table('books', function (Blueprint $table) {
                $table->renameColumn('published_year', 'year');
            });

            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('published_year');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('books', 'year') || Schema::hasColumn('books', 'published_year')) {
            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $table->unsignedSmallInteger('published_year')->nullable()->after('year');
        });

        DB::table('books')->update(['published_year' => DB::raw('year')]);
    }
};
