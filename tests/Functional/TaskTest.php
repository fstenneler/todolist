<?php

namespace App\Tests\Functional;

use App\Entity\Task;
use App\Tests\AuthenticationUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test of task features
 */
class TaskTest extends WebTestCase
{
    private $client = null;
    private $auth;
    private $em;

    /**
     * set up the tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->auth = new AuthenticationUtil($this->client);
        $this->em = self::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Assert that accessing to list tasks route returns results
     *
     * @return void
     */
    public function testListTasks()
    {
        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('div#task-list h4')->count());
    }

    /**
     * Assert that accessing to list tasks do do route returns results
     *
     * @return void
     */
    public function testListTasksToDo()
    {
        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/to-do');
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('div#task-list h4')->count());
    }

    /**
     * Assert that accessing to list tasks done route returns results
     *
     * @return void
     */
    public function testListTasksDone()
    {
        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/done');
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('div#task-list h4')->count());
    }

    /**
     * Assert that creating a task creates a new entry in database
     *
     * @return void
     */
    public function testCreateTask()
    {
        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/create');

        // fix a random number to check if the db last entry would be the same
        $randomNumber = rand(0,9999);

        // send the form
        $form = $crawler->selectButton('Créer la tâche')->form();
        $form['task[title]'] = 'title ' . $randomNumber;
        $form['task[content]'] = 'content ' . $randomNumber;
        $this->client->submit($form);
        
        $crawler = $this->client->followRedirect();

        // load the last db entry
        $task = $this->em->getRepository(Task::class)->findOneBy([], ['id' => 'DESC']);

        $this->assertResponseIsSuccessful();
        $this->assertSame($task->getTitle(), 'title ' . $randomNumber);
        $this->assertSame($task->getContent(), 'content ' . $randomNumber);
    }

    /**
     * Assert that editing a task modify the task in database
     *
     * @return void
     */
    public function testEditTask()
    {
        // load the last db entry
        $task = $this->em->getRepository(Task::class)->findOneBy([], ['id' => 'DESC']);

        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');

        // fix a random number to check if the db last entry would be the same
        $randomNumber = rand(0,9999);

        // send the form
        $form = $crawler->selectButton('Sauvegarder')->form();
        $form['task[title]'] = 'title ' . $randomNumber;
        $form['task[content]'] = 'content ' . $randomNumber;
        $this->client->submit($form);
        
        $crawler = $this->client->followRedirect();

        // load the db entry
        $task = $this->em->getRepository(Task::class)->findOneBy(['id' => $task->getId()], ['id' => 'DESC']);

        $this->assertResponseIsSuccessful();
        $this->assertSame($task->getTitle(), 'title ' . $randomNumber);
        $this->assertSame($task->getContent(), 'content ' . $randomNumber);
    }

    /**
     * Assert that toggling a task modify the task in database
     *
     * @return void
     */
    public function testToggleTask()
    {
        // load the last db entry
        $task = $this->em->getRepository(Task::class)->findOneBy(['isDone' => false], ['id' => 'DESC']);

        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        
        $crawler = $this->client->followRedirect();

        // load the db entry
        $task = $this->em->getRepository(Task::class)->findOneBy(['id' => $task->getId()], ['id' => 'DESC']);

        $this->assertResponseIsSuccessful();
        $this->assertSame($task->isDone(), true);
    }

    /**
     * Assert that deleting a task deletes the task in database
     *
     * @return void
     */
    public function testDeleteTask()
    {
        // load the last db entry
        $task = $this->em->getRepository(Task::class)->findOneBy([], ['id' => 'DESC']);

        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        
        $crawler = $this->client->followRedirect();

        // load the last db entry
        $task = $this->em->getRepository(Task::class)->findOneBy(['id' => $task->getId()]);

        $this->assertResponseIsSuccessful();
        $this->assertSame($task, null);
    }


}