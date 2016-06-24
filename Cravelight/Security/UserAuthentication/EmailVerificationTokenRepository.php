<?php

namespace Cravelight\Security\UserAuthentication;



use Carbon\Carbon;

class EmailVerificationTokenRepository implements IEmailVerificationTokenRepository
{

    public function store(EmailVerificationToken $emailVerificationToken) : EmailVerificationToken
    {
        $model = $this->getModelFor($emailVerificationToken);
        $model->created_at = new Carbon(); // we never update these, they are disposable
        $model->save();
        return $this->fetch($emailVerificationToken->email, $emailVerificationToken->token);
    }

    public function fetch(string $email, string $token) : EmailVerificationToken
    {
        $model = EloquentEmailVerificationToken::where('email', $email)->where('token', $token)->first();
        return is_null($model)
            ? null
            : $this->getPopoFor($model);
    }



    private function getModelFor(EmailVerificationToken $popo) : EloquentEmailVerificationToken
    {
        $model = new EloquentEmailVerificationToken();
        $model->email = $popo->email;
        $model->token = $popo->token;
        $model->expires_at = $popo->expiresAt;
        $model->created_at = $popo->createdAt;
        return $model;
    }

    private function getPopoFor(EloquentEmailVerificationToken $model) : EmailVerificationToken
    {
        $popo = new EmailVerificationToken($model->email);
        $popo->token = $model->token;
        $popo->expiresAt = $model->expires_at;
        $popo->createdAt = $model->created_at;
        return $popo;
    }



}
