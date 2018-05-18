<?php

namespace App\DataFixtures\ORM;

use App\Entity\Page;
use App\Entity\Term;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager) {
        $termRepo = $manager->getRepository(Term::class);
        $user = $manager->getRepository(User::class)->findOneByEmail('info@moroztaras.ua');
        $terms = $termRepo->findAll('Term');
        foreach ($terms as $term){
            $page = new Page();
            $page->setTitle('Перший заголовок тестового посту'.$term->getId());
            $page->setBody('Перший пост'. $term->getId());
            $page->setCategory($term);
            $page->setUser($user);
            $page->setCreated(new \DateTime());
            $manager->persist($page);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TermFixtures::class,
            UserFixtures::class
        ];
    }
}