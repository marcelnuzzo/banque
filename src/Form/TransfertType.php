<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Bankaccount;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\BankaccountRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TransfertType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $beneficiaries = $options["beneficiaries"];
        //dd($beneficiaries);
        for($i=0; $i<2; $i++) {
            $beneficiaries[] = $options["beneficiaries"][$i];
        }
        //dd($beneficiaries);
        $builder
            ->add('amount', TextType::class, [
                'label' => "Entrer un montant",
                'attr' => [
                    'placeholder' => "Veuillez entrer un montant en €"
                ]
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'label' => "Vos bénéficiaires",
                'choice_label' => 'email',
                'choices' => $beneficiaries,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bankaccount::class,
            'beneficiaries' => null,
            // Configure your form options here
        ]);
    }
}
