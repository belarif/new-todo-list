<?php

namespace App\Exception;

use Exception;

class UserException extends Exception
{
    public static function userExists($user)
    {
        return new self("L'utilisateur ". $user->getUsername() . " est déjà existant !");
    }

    public static function notUserExists($id)
    {
        return new self("L'utilisateur d'id " .$id. " n'existe pas");
    }
}