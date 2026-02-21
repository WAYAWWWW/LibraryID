<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // sample book
        Book::create([
            'title' => 'Contoh Buku',
            'author' => 'Penulis A',
            'publisher' => 'Penerbit X',
            'year' => '2020',
            'synopsis' => 'Sinopsis contoh buku.',
            'stock' => 5,
        ]);
    }
}
