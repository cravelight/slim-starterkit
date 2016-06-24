<?php

namespace Cravelight\Security\UserAuthentication;

use Illuminate\Database\Eloquent\Model;

class EloquentEmailVerificationToken extends Model
{
    protected $table = 'email_verification_tokens';
    public $primaryKey = 'token';
    public $incrementing = false;
    public $timestamps = false;
    protected $dates = ['expires_at', 'created_at', 'updated_at'];
}
