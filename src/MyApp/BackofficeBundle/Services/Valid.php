<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validator
 *
 * @author Mehrez
 */

namespace MyApp\BackofficeBundle\Services;


class Valid
{

    public function validerInscri($form)
    {
        $isValid = FALSE;
        $login = $form["login"]->getData();
        $email = $form["email"]->getData();
        $password = $form["password"]->getData();
        if (($login != NULL) && ($email != NULL) && ($password != NULL)) {
            $isValid = TRUE;
        }

        return $isValid;
    }
}
