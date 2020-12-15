<?php

namespace App\Service;

class NewUserAccount {

    public function getNewUserAccount($accounts) {
        
            $nbIban = count($accounts);
            if($nbIban > 0) {
                for($i=0; $i<$nbIban; $i++) {
                    $ibans[] = $accounts[$i]->getIban();
                    $ib[] = explode("-", $ibans[$i]);
                }
                for($i=0; $i<$nbIban; $i++) {
                    $iba[] = $ib[$i][1];
                }
                do {
                    $iban = mt_rand(1000, 2000);
                    $trueIban = !in_array($iban, $iba);
                } while($trueIban != true);
                $iban = "fr-".$iban;
            } else {
                $iban = "fr-1000";
            }
            return $iban;

    }
}