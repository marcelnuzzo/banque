<?php

namespace App\Service;

class Transaction {

    public function desrinatary($form)
    {
        $ibanDest = $form->getData()->getIban();
        
        return $ibanDest;
    }

    public function creditDebit($form, $oldAmountUser, $destAccount)
    {
        $oldAmount = $destAccount[0]->getAmount(); 
        $newAmount = $form->getData()->getAmount();  
        $balance = $oldAmount + $newAmount; 
        $balanceUser = $oldAmountUser - $newAmount; 
        
        return compact('balance', 'balanceUser', 'newAmount');

    }
}