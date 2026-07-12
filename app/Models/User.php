<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

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
        'username',
        'email',
        'password',
        'phone',
        'photo',
        'gender',
        'dob',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'status',
        'role',
        'two_factor_code',
        'two_factor_expires_at',
        'tenant_id',
        'last_login_ip',
        'last_login_at',
        'api_token',
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
        ];
    }

    // app/Models/User.php

    public function latestMessage()
    {

        return $this->hasOne(Chat::class, 'sender_id')
            ->where(function ($query) {
                $query->where('receiver_id', Auth::id())
                    ->orWhere('sender_id', Auth::id());
            })
            ->latest('created_at');
    }

    public function unreadMessages()
    {
        return $this->hasMany(Chat::class, 'sender_id')
            ->where('receiver_id', Auth::id())
            ->where('is_read', false);
    }
}
