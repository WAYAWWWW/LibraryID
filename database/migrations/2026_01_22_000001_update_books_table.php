<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('books', 'publication_year')) {
                $table->year('publication_year')->nullable()->after('year');
            }
            if (!Schema::hasColumn('books', 'pages')) {
                $table->unsignedInteger('pages')->nullable()->after('publication_year');
            }
            if (!Schema::hasColumn('books', 'category')) {
                $table->string('category')->nullable()->after('pages');
            }
            if (!Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable()->after('category');
            }
            if (!Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'publication_year',
                'pages',
                'category',
                'description',
                'cover_image'
            ]);
        });
    }
};
