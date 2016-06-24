<?php

namespace Cravelight\Security\UserAuthentication;

use Carbon\Carbon;


class EmailAccessCredentialRepository implements IEmailAccessCredentialRepository
{

    public function store(EmailAccessCredential $emailAccessCredential) : EmailAccessCredential
    {
        $model = $this->getModelFor($emailAccessCredential);
        $now = new Carbon();
        $model->created_at = $model->created_at ?? $now;
        $model->updated_at = $now;
        $model->save();
        return $this->fetchForEmailAddress($emailAccessCredential->email);
    }

    public function fetchForEmailAddress(string $emailAddress) : EmailAccessCredential
    {
        $model = EloquentEmailAccessCredential::find($emailAddress);
        return $this->getPopoFor($model);
    }



    private function getModelFor(EmailAccessCredential $popo) : EloquentEmailAccessCredential
    {
        $model = new EloquentEmailAccessCredential();
        $model->email = $popo->email;
        $model->password_hash = $popo->passwordHash;
        $model->verified_at = $popo->verifiedAt;
        $model->created_at = $popo->createdAt;
        $model->updated_at = $popo->updatedAt;
        return $model;
    }

    private function getPopoFor(EloquentEmailAccessCredential $model) : EmailAccessCredential
    {
        $popo = new EmailAccessCredential($model->email);
        $popo->passwordHash = $model->password_hash;
        $popo->verifiedAt = $model->verified_at;
        $popo->createdAt = $model->created_at;
        $popo->updatedAt = $model->updated_at;
        return $popo;
    }

}
