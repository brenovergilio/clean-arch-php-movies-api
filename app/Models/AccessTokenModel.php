<?php

namespace App\Models;

use App\Core\Domain\Entities\AccessToken\AccessToken;
use App\Core\Domain\Entities\AccessToken\AccessTokenIntent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessTokenModel extends Model
{
    use HasFactory;

    protected $table = "access_tokens";
    protected $primaryKey = "token";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'time_to_leave',
        'user_id',
        'intent'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'time_to_leave' => 'int'
    ];

    public function user() {
        return $this->belongsTo(UserModel::class);
    }

    public function mapToDomain(): AccessToken {
        return new AccessToken(
            AccessTokenModel::mapIntentToDomain($this->intent),
            $this->user_id,
            $this->time_to_leave,
            $this->created_at,
            $this->token            
        );
    }

    public static function mapIntentToDomain(string $intent): AccessTokenIntent {
        return AccessTokenIntent::from($intent);
    }

    public static function mapIntentToModel(AccessTokenIntent $intent): string {
        return $intent->value;
    }
}
