<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    const ACTIVE = 1;
    const CLOSE = 0;
    const PENDING = 2;

    protected $fillable = [
        'title','user_id','user_assign_id','status'
    ];

    public $timestamps = true;


    public function getStatusNameAttribute()
    {
        return array_search($this->status,[
            'ACTIVE'  => self::ACTIVE ,
            'PASSIVE' => self::CLOSE  ,
            'PENDING' => self::PENDING
        ]);
    }

    public function getAssignedUserAttribute($value)
    {
        return User::find($this->user_assign_id)->name;
    }

    public function getCreatedUserAttribute($value)
    {
        return User::find($this->user_id)->name;
    }


    public function scopeStatuses(){
        return [
            self::ACTIVE =>'ACTIVE',
            self::CLOSE =>'PASSIVE',
            self::PENDING =>'PENDING'
        ];
    }



}
