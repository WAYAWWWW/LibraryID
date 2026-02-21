<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email');
            $table->boolean('is_fake')->default(false)->after('role');
            $table->unsignedBigInteger('registered_by')->nullable()->after('is_fake');
            $table->foreign('registered_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['registered_by']);
            $table->dropColumn(['registered_by', 'is_fake', 'role']);
        });
    }
};
