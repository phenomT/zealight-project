<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'photo',
        'type',
        'email',
        'password',
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

    // User.php

    public function details()
    {
        return $this->hasMany(Detail::class);
    }


    // User.php

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];


     /**
     * Retrieve the user's photo or a default image.
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {

        $defaultImage = 'images/not_available.png';


        if (!is_null($this->attributes['photo'])) {

            return $this->attributes['photo'];
        }

        return $defaultImage;
    }


    /**
     * Retrieve the user's full name.
     *
     * @return string
     */
    public function getFullnameAttribute(): string
    {

        $fullname = $this->attributes['firstname'] . ' ' . $this->attributes['lastname'];

        if (!empty($this->attributes['middlename'])) {
            $fullname .= ' ' . $this->attributes['middlename'];
        }

        return $fullname;
    }

    /**
     * Retrieve the user's middle initial.
     *
     * @return string
     */
    public function getMiddleinitialAttribute(): string
    {

        $middlename = $this->attributes['middlename'];

        if (!empty($middlename)) {

            return strtoupper(substr($middlename, 0, 1)) . '.';
        }

        return '';
    }
}
