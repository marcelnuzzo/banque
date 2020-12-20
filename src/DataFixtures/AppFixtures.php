<?php

namespace App\DataFixtures;

use App\Entity\Bankaccount;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $user = new User();
        $user->setFirstname("Marcel")
            ->setLastname("Nuzzo")
            ->setEmail("nuzzo.marcel@aliceadsl.fr")
            ->setBirthdayAt(new \DateTime('1968-04-13'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '1234'
            ));
        
        $manager->persist($user);

        $user = new User();
        $user->setFirstname("Romain")
            ->setLastname("Desajardim")
            ->setEmail("romain.desajardim@gmail.com")
            ->setBirthdayAt(new \DateTime('1990-01-01'))
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '1234'
            ));
        
        $manager->persist($user);

        $tabFirstname = [
            "Madeleine",
            "Sophie",
            "André"
        ];
        $tabLastname = [
            "Dupond",
            "Durand",
            "César"
        ];

        for($i=1; $i<=3; $i++) {
           
            $y = mt_rand(1968, 2001);
            $m = mt_rand(1, 12);
            $d =mt_rand(1, 30);
            $user = new User();
            $user->setFirstname($tabFirstname[$i-1])
                ->setLastname($tabLastname[$i-1])
                ->setEmail($tabFirstname[$i-1].$tabLastname[$i-1]."@gmail")
                ->setBirthdayAt(new \DateTime($y."-".$m."-".$d))
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    '1234')
                );
            
                $manager->persist($user);
            
                for($j=1; $j<=2; $j++) {
                    $createdAt = $faker->dateTimeBetween('-6 months');
                    $bank = new Bankaccount();
                    $bank->setIban("fr-1".mt_rand(111, 999))
                        ->setAmount(mt_rand(1, 9)."000")
                        ->setCreatedAt($createdAt)
                        ->setUsers($user);

                $manager->persist($bank);
                }
        }
        
               


        $manager->flush();
    }
}
