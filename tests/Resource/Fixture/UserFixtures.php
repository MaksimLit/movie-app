<?php
declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\Entity\User;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    use FakerTools;

    const REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        $email = $this->getFaker()->email;
        $password = $this->getFaker()->password;
        $user = (new User())->setEmail($email)->setPassword($password)->setRoles([User::ROLE_USER]);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }
}
