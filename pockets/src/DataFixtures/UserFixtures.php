<?php
declare (strict_types = 1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct (UserPasswordEncoderInterface $passwordEncoder)
    {
        $this -> passwordEncoder = $passwordEncoder;
    }

    public function load (ObjectManager $manager)
    {
        $user = new User ();

        $user
            -> setFirstName ("Barbara")
            -> setLastName ("Zych")
            -> setEmail ("admin@localhost.com")
            -> setPassword ($this -> passwordEncoder -> encodePassword ($user, "adminpassword1234567890"))
            -> setRoles (["ROLE_USER", "ROLE_ADMIN"])
            ;

        $manager -> persist ($user);

        $user = new User ();

        $user
            -> setFirstName ("Elon")
            -> setLastName ("Musk")
            -> setEmail ("elon.musk@spacex.com")
            -> setPassword ($this -> passwordEncoder -> encodePassword ($user, "password1234567890"))
            -> setRoles (["ROLE_USER"])
        ;

        $manager -> persist ($user);

        $user = new User ();

        $user
            -> setFirstName ("Jeff")
            -> setLastName ("Bezos")
            -> setEmail ("jeff.bezos@amazon.com")
            -> setPassword ($this -> passwordEncoder -> encodePassword ($user, "password1234567890"))
            -> setRoles (["ROLE_USER"])
        ;

        $manager -> persist ($user);

        $manager -> flush ();
    }
}