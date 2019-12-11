<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadTasks($manager);
    }

    /**
     * Load users and store results into database
     *
     * @param ObjectManager $manager
     * @return void
     */
    private function loadUsers(ObjectManager $manager)
    {

        // initiate the faker bundle
        $faker = Faker\Factory::create('fr_FR');

        // create 1 admin
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin')); 
        $user->setEmail($faker->email);
        $manager->persist($user);

        // create 10 users
        for ($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'user')); 
            $user->setEmail($faker->email);
            $manager->persist($user);
            $this->addReference('[user] ' . $i, $user);

        }

        $manager->flush();

    }

    /**
     * Load tasks and store results into database
     *
     * @param ObjectManager $manager
     * @return void
     */
    private function loadTasks(ObjectManager $manager)
    {

        // initiate the faker bundle
        $faker = Faker\Factory::create('fr_FR');

        // create 30 tasks
        for ($i = 0; $i < 30; $i++) {

            // random values
            $randDone = false;
            if(1 === rand(0,1)) {
                $randDone = true;
            }

            $task = new Task();
            $task->setCreatedAt(
                $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = 'Europe/Paris')
            );
            $task->setTitle(
                preg_replace("#.$#", "", $faker->sentence($nbWords = 3, $variableNbWords = true))
            );
            $task->setContent(
                $faker->sentence($nbWords = 10, $variableNbWords = true)
            );
            $task->toggle($randDone);
            $manager->persist($task);
            $this->addReference('[task] ' . $i, $task);

        }

        $manager->flush();

    }

}
