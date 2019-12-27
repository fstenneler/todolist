<?php

namespace App\Tests\Database;

use App\Entity\Task;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseTest extends WebTestCase
{
    use FixturesTrait;

    protected $em;

    /**
     * Set up the tests
     *
     * @return void
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        // get doctrine
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->loadFixtures([
            'App\DataFixtures\AppFixtures'
        ]);
    }

    /**
     * Test all tables, entities and repositories
     *
     */
    public function testFixtures()
    {
        // Tasks
        $count = $this->em
            ->getRepository(Task::class)
            ->count([]);
        $this->assertGreaterThan(0, $count);

        // Users
        $count = $this->em
            ->getRepository(User::class)
            ->count([]);
        $this->assertGreaterThan(0, $count);
    }

}