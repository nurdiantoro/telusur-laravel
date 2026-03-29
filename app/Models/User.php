<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /**
     * Trait yang digunakan:
     * - HasFactory     : untuk keperluan factory (seeder/testing)
     * - Notifiable     : untuk fitur notifikasi (email, dll)
     * - SoftDeletes    : agar data tidak benar-benar dihapus (pakai deleted_at)
     */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Kolom yang boleh diisi secara mass assignment
     * (misalnya saat create / update dengan request)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat model di-serialize
     * (misalnya dikirim ke API / JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis
     * - email_verified_at → datetime
     * - password → otomatis di-hash
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi Many-to-Many:
     * User bisa punya banyak Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Cek apakah user punya role tertentu
     *
     * contoh pemakaian:
     * if ($user->hasRole('admin')) {
     */
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Cek apakah user punya permission tertentu
     * (diambil dari semua role yang dimiliki user)
     *
     * contoh pemakaian:
     * if ($user->hasPermission('edit-post')) {
     */
    public function hasPermission($permission)
    {
        return $this->roles
            ->flatMap->permissions // gabungkan semua permission dari tiap role
            ->contains('name', $permission);
    }

    /**
     * Cek apakah user boleh mengakses panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
