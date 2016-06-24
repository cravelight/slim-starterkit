<?php

namespace Cravelight\Security\UserAuthentication;

use Illuminate\Database\Eloquent\Model;

class EloquentEmailAccessCredential extends Model
{
    protected $table = 'email_access_credentials';
    public $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;
    protected $dates = ['verified_at', 'created_at', 'updated_at'];
}
