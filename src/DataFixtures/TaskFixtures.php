<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public static array $tasks = [
        ['title' => 'première tache', 'content' => 'Contenu de la 1ère tache'],
        ['title' => 'deuxième tache', 'content' => 'Contenu de la 2ème tache'],
        ['title' => 'troisième tache', 'content' => 'Contenu de la 3ème tache']
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (TaskFixtures::$tasks as $newTask) {
            $task = new Task();

            $task->setTitle($newTask['title']);
            $task->setContent($newTask['content']);

            $manager->persist($task);
            $manager->flush();
        }
    }
}
