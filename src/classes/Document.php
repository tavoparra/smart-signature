<?php

namespace SmartSignature;

use Illuminate\Database\Eloquent\Model;

class Document extends Model {
    protected $fillable = ['document', 'owner_id', 'authorizer_id', 'status', 'signature'];

    public function owner() {
        return $this->belongsTo('SmartSignature\User', 'owner_id');
    }

    public function authorizer() {
        return $this->belongsTo('SmartSignature\User', 'authorizer_id');
    }
}
