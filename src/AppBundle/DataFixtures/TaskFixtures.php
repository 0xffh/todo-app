<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use Badcow\LoremIpsum\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $generator = new Generator();

        for ($i = 0; $i <= 1; $i++) {
            $task = new Task($this->getReference('user-one'));

            $paragraphs = $generator->getSentences(2);

            $task->setContent(implode(' ', $paragraphs));

            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}