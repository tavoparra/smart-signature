<?php

namespace SmartSignature;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    public $timestamps = false;
    protected $fillable = ['username', 'password', 'name'];

    public function documents() {
        return $this->hasMany('SmartSignature\Document', 'owner_id');
    }

    public function authorizations() {
        return $this->hasMany('SmartSignature\Document', 'authorizer_id');
    }
}
