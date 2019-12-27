<?php

namespace App\Tests\Functional;

use App\Entity\User;
use App\Tests\AuthenticationUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test of task features
 */
class UserTest extends WebTestCase
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
     * Assert that accessing to list users route returns results
     *
     * @return void
     */
    public function testListUsers()
    {
        $this->auth->logIn('ROLE_ADMIN');
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('div.user-list tr')->count());
    }

    /**
     * Assert that creating a user creates a new entry in database
     *
     * @return void
     */
    public function testCreateUser()
    {
        $this->auth->logIn('ROLE_ADMIN');
        $crawler = $this->client->request('GET', '/users/create');

        // fix a random number to check if the db last entry would be the same
        $randomNumber = rand(0,9999);

        // send the form
        $form = $crawler->selectButton('Créer l\'utilisateur')->form();
        $form['user[username]'] = 'username' . $randomNumber;
        $form['user[password][first]'] = 'Aa000000' . $randomNumber;
        $form['user[password][second]'] = 'Aa000000' . $randomNumber;
        $form['user[email]'] = 'test@orlinstreet.rocks' . $randomNumber;
        $this->client->submit($form);
        
        $crawler = $this->client->followRedirect();

        // load the last db entry
        $user = $this->em->getRepository(User::class)->findOneBy([], ['id' => 'DESC']);

        $this->assertResponseIsSuccessful();
        $this->assertSame($user->getUsername(), 'username' . $randomNumber);
        $this->assertSame($user->getEmail(), 'test@orlinstreet.rocks' . $randomNumber);
    }

    /**
     * Assert that editing a user modify the user in database
     *
     * @return void
     */
    public function testEditUser()
    {
        // load the last db entry
        $user = $this->em->getRepository(User::class)->findOneBy([], ['id' => 'DESC']);

        $this->auth->logIn('ROLE_ADMIN');
        $crawler = $this->client->request('GET', '/users/' . $user->getId() . '/edit');

        // fix a random number to check if the db last entry would be the same
        $randomNumber = rand(0,9999);

        // send the form
        $form = $crawler->selectButton('Modifier l\'utilisateur')->form();
        $form['user[username]'] = 'username' . $randomNumber;
        $form['user[password][first]'] = 'Aa000000' . $randomNumber;
        $form['user[password][second]'] = 'Aa000000' . $randomNumber;
        $form['user[email]'] = 'test@orlinstreet.rocks' . $randomNumber;
        $this->client->submit($form);
        
        $crawler = $this->client->followRedirect();

        // load the db entry
        $task = $this->em->getRepository(User::class)->findOneBy(['id' => $user->getId()], ['id' => 'DESC']);

        $this->assertResponseIsSuccessful();
        $this->assertSame($task->getUsername(), 'username' . $randomNumber);
        $this->assertSame($task->getEmail(), 'test@orlinstreet.rocks' . $randomNumber);
    }

}