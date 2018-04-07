<?php

namespace Core\Interfaces;

interface LoginInterface {

    public function login($username, $password);

    public function changePassword($username, $oldPassword, $newPassword, $repeatPassword);

    public function getErrors();
}
