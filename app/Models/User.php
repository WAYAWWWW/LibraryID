<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_fake',
        'registered_by',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected $table = 'user';

    public function loans()
    {
        return $this->hasMany(\App\Models\Loan::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    /**
     * Check if user has active loans that haven't been returned
     * @return bool
     */
    public function hasActiveLoan(): bool
    {
        return $this->loans()
            ->whereIn('status', ['pending', 'approved', 'active', 'returning'])
            ->exists();
    }

    /**
     * Get active loans count
     * @return int
     */
    public function getActiveLoanCount(): int
    {
        return $this->loans()
            ->whereIn('status', ['pending', 'approved', 'active', 'returning'])
            ->count();
    }
}
