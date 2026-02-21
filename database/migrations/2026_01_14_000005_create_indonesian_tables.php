<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // kategoribuku
        if (! Schema::hasTable('kategoribuku')) {
            Schema::create('kategoribuku', function (Blueprint $table) {
                $table->id('KategoriID');
                $table->string('NamaKategori',255);
                $table->timestamps();
            });
        }

        // buku (if not exists)
        if (! Schema::hasTable('buku')) {
            Schema::create('buku', function (Blueprint $table) {
                $table->id('BukuID');
                $table->string('Judul',255);
                $table->string('Penulis',255)->nullable();
                $table->string('Penerbit',255)->nullable();
                $table->integer('TahunTerbit')->nullable();
                $table->text('Sinopsis')->nullable();
                $table->unsignedInteger('Stok')->default(0);
                $table->unsignedInteger('ReadsCount')->default(0);
                $table->timestamps();
            });

            // copy from books if exists
            if (Schema::hasTable('books')) {
                DB::table('buku')->insertUsing(
                    ['Judul','Penulis','Penerbit','TahunTerbit','Sinopsis','Stok','ReadsCount','created_at','updated_at'],
                    DB::table('books')->select('title as Judul','author as Penulis','publisher as Penerbit','year as TahunTerbit','synopsis as Sinopsis','stock as Stok','reads_count as ReadsCount','created_at','updated_at')
                );
            }
        }

        // kategoribuku_relasi
        if (! Schema::hasTable('kategoribuku_relasi')) {
            Schema::create('kategoribuku_relasi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('BukuID');
                $table->unsignedBigInteger('KategoriID');
                $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
                $table->foreign('KategoriID')->references('KategoriID')->on('kategoribuku')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // user table alias if not exists: create `user` for compatibility
        if (! Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->id('UserID');
                $table->string('Username',255)->nullable();
                $table->string('Password',255)->nullable();
                $table->string('Email',255)->nullable();
                $table->string('NamaLengkap',255)->nullable();
                $table->text('Alamat')->nullable();
                $table->timestamps();
            });

            // copy from users
            if (Schema::hasTable('users')) {
                DB::table('user')->insertUsing(
                    ['UserID','Username','Password','Email','NamaLengkap','Alamat','created_at','updated_at'],
                    DB::table('users')->select('id as UserID','email as Username','password as Password','email as Email','name as NamaLengkap',DB::raw('NULL as Alamat'),'created_at','updated_at')
                );
            }
        }

        // peminjaman
        if (! Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id('PeminjamanID');
                $table->unsignedBigInteger('UserID');
                $table->unsignedBigInteger('BukuID');
                $table->date('TanggalPeminjaman')->nullable();
                $table->date('TanggalPengembalian')->nullable();
                $table->string('StatusPeminjaman',50)->nullable();
                $table->timestamps();
                $table->foreign('UserID')->references('UserID')->on('user')->onDelete('cascade');
                $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            });

            // copy from loans
            if (Schema::hasTable('loans')) {
                DB::table('peminjaman')->insertUsing(
                    ['UserID','BukuID','TanggalPeminjaman','TanggalPengembalian','StatusPeminjaman','created_at','updated_at'],
                    DB::table('loans')->select('user_id as UserID','book_id as BukuID','borrow_date as TanggalPeminjaman','due_date as TanggalPengembalian','status as StatusPeminjaman','created_at','updated_at')
                );
            }
        }

        // ulasanbuku
        if (! Schema::hasTable('ulasanbuku')) {
            Schema::create('ulasanbuku', function (Blueprint $table) {
                $table->id('UlasanID');
                $table->unsignedBigInteger('UserID');
                $table->unsignedBigInteger('BukuID');
                $table->text('Ulasan')->nullable();
                $table->tinyInteger('Rating')->nullable();
                $table->timestamps();
                $table->foreign('UserID')->references('UserID')->on('user')->onDelete('cascade');
                $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            });

            if (Schema::hasTable('reviews')) {
                DB::table('ulasanbuku')->insertUsing(
                    ['UserID','BukuID','Ulasan','Rating','created_at','updated_at'],
                    DB::table('reviews')->select('user_id as UserID','book_id as BukuID','comment as Ulasan','rating as Rating','created_at','updated_at')
                );
            }
        }

        // koleksipribadi (user personal collections)
        if (! Schema::hasTable('koleksipribadi')) {
            Schema::create('koleksipribadi', function (Blueprint $table) {
                $table->id('KoleksiID');
                $table->unsignedBigInteger('UserID');
                $table->unsignedBigInteger('BukuID');
                $table->timestamps();
                $table->foreign('UserID')->references('UserID')->on('user')->onDelete('cascade');
                $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('koleksipribadi');
        Schema::dropIfExists('ulasanbuku');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('user');
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('kategoribuku');
    }
};
