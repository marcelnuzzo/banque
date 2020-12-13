<?php

namespace App\Service;

class NewUserAccount {

    public function getNewUserAccount($account) {
        
            $nbIban = count($account);
            for($i=0; $i<$nbIban; $i++) {
                $ibans[] = $account[$i]->getIban();
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

            return $iban;

    }
}