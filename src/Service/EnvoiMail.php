<?php

namespace App\Service;

class EnvoiMail
{
    public function envoi($user, $compte, $montant, $userOrigin)
    {
        $mdp = "1234";
        $body="Iban : ".$compte.'</br>'."Montant : ".$montant.'</br>'."Client : ".$userOrigin->getFullname().'</br>'."Prénom de bénéficiare : ".$user->getFirstname().'</br>'."Nom du bénéficiaire : ".$user->getLastname().'</br>'."Email du bénéficiaire : ".$user->getEmail().'</br>'."Mot de passe : ".$mdp
        ;
        $message = (new \Swift_Message('Création bénéficiaire'))          
                ->setFrom('nuzzom98@gmail.com')
                ->setTo('nuzzo.marcel@aliceadsl.fr')
                ->setBody($body,
                        'text/html'
            );
            
       return $message;

    }

}