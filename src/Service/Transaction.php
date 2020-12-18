<?php

namespace App\Service;

class Transaction {

    public function creditDebit($form, $repo, $idUser, $oldAmountUser)
    {
        $idDest = $form->getData()->getUsers()->getId();  
        $dest = $repo->findDestinatary($idUser, $idDest);
        $oldAmount = $dest[0]->getAmount(); 
        $newAmount = $form->getData()->getAmount();     
        $balance = $oldAmount + $newAmount; 
        $balanceUser = $oldAmountUser - $newAmount; 

        return compact('dest', 'balance', 'balanceUser');

    }
}