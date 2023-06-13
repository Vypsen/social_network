<?php

namespace App\Modules\User\Entities;

use App\Modules\Patient\Entities\Patient;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'midname',
        'number_phone',
        'email',
        'gender',
        'bdate'
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
        'registration_at' => 'datetime',
    ];

    public static function createFormRequest(array $requestData): self
    {
        $user = new self();
        $user->name = $requestData['name'];
        $user->lastname = $requestData['lastname'];
        $user->midname = $requestData['midname'];
        $user->gender = $requestData['gender'];
        $user->bdate = strtotime($requestData['bdate']);
        $user->number_phone = $requestData['number_phone'];
        $user->email = $requestData['email'];
        $user->password = Hash::make($requestData['password']);

        return $user;
    }

    protected static function newFactory()
    {
        return \App\Modules\User\Database\factories\UserFactory::new();
    }

    public function getFullName()
    {
        return "$this->lastname $this->name $this->midname";
    }

    public function getAge()
    {
        $age = floor((time() - strtotime($this->bdate)) / 31556926);
        return $age;
    }
}
