<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Core\Domain\Entities\User\Role;
use App\Core\Domain\Entities\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'email_verified_at',
        'role',
        'photo',
        'email_confirmed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function mapToDomain(): User {
        return new User(
            $this->id,
            $this->name,
            $this->cpf,
            $this->email,
            $this->password,
            $this->mapRoleToDomain(),
            $this->photo,
            $this->email_confirmed
        );
    }

    private function mapRoleToDomain(): Role {
        return $this->role === 'admin' ? Role::ADMIN : Role::CLIENT;
    }
}
