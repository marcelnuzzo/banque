<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $withoutPw = $options['withoutPw'];
        //dd($withoutPw);
            $builder
                ->add('email')
            //->add('roles')
            ;
            if($withoutPw) {
                $builder
                ->add('password', TextType::class)
                ;
            }
            $builder
                ->add('firstname')
                ->add('lastname')
                ->add('birthdayAt', DateType::class, [
                    'widget' => 'single_text',
                ])
                ;
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'withoutPw' => null,
        ]);
    }
}
