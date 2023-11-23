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
            UserModel::mapRoleToDomain($this->role),
            $this->photo,
            $this->email_confirmed
        );
    }

    public static function mapRoleToDomain(string $role): Role {
        return $role === 'admin' ? Role::ADMIN : Role::CLIENT;
    }

    public function mergeDomain(User $user): void {
        $this->name = $user->name();
        $this->cpf = $user->cpf();
        $this->email = $user->email();
        $this->password = $user->password();
        $this->role = UserModel::mapRoleToModel($user->role());
        $this->photo = $user->photo();
        $this->email_confirmed = $user->isEmailConfirmed();
    }

    public static function mapRoleToModel(Role $role): string {
        return $role === Role::ADMIN ? 'admin' : 'client';
    }
}
