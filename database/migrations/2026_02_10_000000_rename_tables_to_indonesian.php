<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop Indonesian tables if they already exist (as requested)
        Schema::dropIfExists('ulasanbuku');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('koleksipribadi');
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('kategoribuku');
        Schema::dropIfExists('user');
        Schema::dropIfExists('buku');

        $this->dropForeignIfExists('loans', ['user_id']);
        $this->dropForeignIfExists('loans', ['book_id']);
        $this->dropForeignIfExists('reviews', ['loan_id']);
        $this->dropForeignIfExists('reviews', ['user_id']);
        $this->dropForeignIfExists('reviews', ['book_id']);
        $this->dropForeignIfExists('wishlists', ['user_id']);
        $this->dropForeignIfExists('wishlists', ['book_id']);
        $this->dropForeignIfExists('users', ['registered_by']);

        $renames = [
            'books' => 'buku',
            'users' => 'user',
            'loans' => 'peminjaman',
            'reviews' => 'ulasanbuku',
            'wishlists' => 'koleksipribadi',
        ];

        foreach ($renames as $from => $to) {
            if (!Schema::hasTable($from)) {
                continue;
            }

            if (Schema::hasTable($to)) {
                Schema::drop($to);
            }

            Schema::rename($from, $to);
        }

        $this->addForeignIfExists('peminjaman', 'user_id', 'user', 'id');
        $this->addForeignIfExists('peminjaman', 'book_id', 'buku', 'id');
        $this->addForeignIfExists('ulasanbuku', 'loan_id', 'peminjaman', 'id');
        $this->addForeignIfExists('ulasanbuku', 'user_id', 'user', 'id');
        $this->addForeignIfExists('ulasanbuku', 'book_id', 'buku', 'id');
        $this->addForeignIfExists('koleksipribadi', 'user_id', 'user', 'id');
        $this->addForeignIfExists('koleksipribadi', 'book_id', 'buku', 'id');
        $this->addForeignIfExists('user', 'registered_by', 'user', 'id', 'set null');

        if (Schema::hasTable('koleksipribadi')) {
            $indexName = 'koleksipribadi_user_id_book_id_unique';
            if (! $this->indexExists('koleksipribadi', $indexName)) {
                Schema::table('koleksipribadi', function (Blueprint $table) {
                    $table->unique(['user_id', 'book_id']);
                });
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->dropForeignIfExists('peminjaman', ['user_id']);
        $this->dropForeignIfExists('peminjaman', ['book_id']);
        $this->dropForeignIfExists('ulasanbuku', ['loan_id']);
        $this->dropForeignIfExists('ulasanbuku', ['user_id']);
        $this->dropForeignIfExists('ulasanbuku', ['book_id']);
        $this->dropForeignIfExists('koleksipribadi', ['user_id']);
        $this->dropForeignIfExists('koleksipribadi', ['book_id']);
        $this->dropForeignIfExists('user', ['registered_by']);

        $renames = [
            'buku' => 'books',
            'user' => 'users',
            'peminjaman' => 'loans',
            'ulasanbuku' => 'reviews',
            'koleksipribadi' => 'wishlists',
        ];

        foreach ($renames as $from => $to) {
            if (!Schema::hasTable($from)) {
                continue;
            }

            if (Schema::hasTable($to)) {
                Schema::drop($to);
            }

            Schema::rename($from, $to);
        }

        $this->addForeignIfExists('loans', 'user_id', 'users', 'id');
        $this->addForeignIfExists('loans', 'book_id', 'books', 'id');
        $this->addForeignIfExists('reviews', 'loan_id', 'loans', 'id');
        $this->addForeignIfExists('reviews', 'user_id', 'users', 'id');
        $this->addForeignIfExists('reviews', 'book_id', 'books', 'id');
        $this->addForeignIfExists('wishlists', 'user_id', 'users', 'id');
        $this->addForeignIfExists('wishlists', 'book_id', 'books', 'id');
        $this->addForeignIfExists('users', 'registered_by', 'users', 'id', 'set null');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Schema::enableForeignKeyConstraints();
    }

    private function dropForeignIfExists(string $table, array $columns): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                continue;
            }

            $rows = DB::select(
                'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL',
                [$table, $column]
            );

            foreach ($rows as $row) {
                $constraint = $row->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
            }
        }
    }

    private function addForeignIfExists(
        string $table,
        string $column,
        string $refTable,
        string $refColumn,
        ?string $onDelete = 'cascade'
    ): void {
        if (!Schema::hasTable($table) || !Schema::hasTable($refTable) || !Schema::hasColumn($table, $column)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column, $refTable, $refColumn, $onDelete) {
            $foreign = $table->foreign($column)->references($refColumn)->on($refTable);
            if ($onDelete) {
                $foreign->onDelete($onDelete);
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
