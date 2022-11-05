<?php
declare(strict_types=1);

namespace App\Tests\Resource\Fixture;

use App\Entity\Movie;
use App\Tests\Tools\FakerTools;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    use FakerTools;

    const REFERENCE = 'movie';

    public function load(ObjectManager $manager)
    {
        $viewer = $this->getReference(UserFixtures::REFERENCE);

        $name = $this->getFaker()->name;
        $year = $this->getFaker()->year;
        $desc = $this->getFaker()->realText(40);
        $kpId = $this->getFaker()->randomNumber(6);
        $ratingImdb = $this->getFaker()->randomFloat(2);
        $ratingKp = $this->getFaker()->randomFloat(2);
        $posterUrl = $this->getFaker()->imageUrl(640, 480, 'animals', true);

        $movie = (new Movie())
            ->setName($name)
            ->setYear((int) $year)
            ->setDescription($desc)
            ->setKpId($kpId)
            ->setRatingKp($ratingKp)
            ->setRatingImdb($ratingImdb)
            ->setPosterUrl($posterUrl)
            ->addViewer($viewer);

        $manager->persist($movie);
        $manager->flush();

        $this->addReference(self::REFERENCE, $movie);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
