<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\DocumentType;
use App\Enums\UserStatus;
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
        'phone',
        'status',
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
            'status' => UserStatus::class,
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function documents()
    {
        // i want to return personal documents only
        return $this->hasMany(Document::class)->whereIn('type', DocumentType::personalDocumentTypes());
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    public function bookingsOwner()
    {
        return $this->hasMany(Booking::class, 'owner_id');
    }
    public function bookingsClient()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function routeNotificationForVonage($notification): ?string
    {
        // întoarce numărul în format E.164 (+407xxxxxxxx)
        return $this->phone ?: null;
    }

    public function bankAccount()
    {
        return $this->hasOne(OwnerBankAccount::class);
    }

    public function isCompanyOwner(): bool
    {
        return $this->roles()->where('name', 'company-owner')->exists();
    }
}
