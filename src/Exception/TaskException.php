<?php

namespace App\Exception;

use Exception;

class TaskException extends Exception
{
    public static function notTaskExists($id)
    {
        return new self("La tache d'id " .$id. " n'existe pas");
    }
}
