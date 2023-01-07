<?php

namespace App\Models;

use App\Http\Resources\GuestUserResource;
use App\Http\Resources\UserResource;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Passenger (User Profile) Table Relation
    public function userProfile()
    {
        return $this->hasOne('App\UserProfile', 'user_id');
    }

    // Passenger Address Table Relation
    public function address()
    {
        return $this->hasOne('App\PassengerAddress', 'pa_user_id');
    }

    // Transaction ID Table Relation
    public function TransactionId()
    {
        return $this->hasMany('App\TransactionId', 'trx_user_id');
    }

    // Customer Credit Card Table Relation
    public function CustomerCreditCard()
    {
        return $this->hasMany('App\CustomerCreditCard', 'ccc_user_id');
    }

    // Passenger Account Table Relation
    public function PassengerAccount()
    {
        return $this->hasMany('App\PassengerAccount', 'pc_target_id');
    }

    // Passenger Rating Table Relation
    public function PassengerRating()
    {
        return $this->hasMany('App\PassengerRating', 'pr_passenger_id');
    }

    // User Resource Table Relation
    public static function getuser($id){

        $user = UserResource::collection(User::where('id',$id)->get());
        return $user[0];
    }


}
