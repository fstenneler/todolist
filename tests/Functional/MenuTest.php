<?php

namespace App\Tests\Functional;

use App\Tests\BrowserKitUtil;
use App\Tests\AuthenticationUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test of app menu
 */
class MenuTest extends WebTestCase
{
    private $client = null;
    private $auth;
    private $browserKit;

    /**
     * Set up the tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->auth = new AuthenticationUtil($this->client);
        $this->browserKit = new BrowserKitUtil();
    }

    /**
     * Assert that clicking on the connect link redirects to login route
     *
     * @return void
     */
    public function testClickConnect()
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->followRedirect();
        $crawler = $this->client->clickLink('Se connecter');
        $this->assertSame(
            '/login',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the tasks list link redirects to tasks list route
     *
     * @return void
     */
    public function testClickListTasks()
    {
        $this->auth->logIn();
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Toutes les tâches');
        $this->assertSame(
            '/tasks',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the tasks list to do link redirects to tasks list to do route
     *
     * @return void
     */
    public function testClickListTasksToDo()
    {
        $this->auth->logIn();
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Tâches à effectuer');
        $this->assertSame(
            '/tasks/to-do',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the tasks done list link redirects to tasks done list route
     *
     * @return void
     */
    public function testClickListTasksDone()
    {
        $this->auth->logIn();
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Tâches terminées');
        $this->assertSame(
            '/tasks/done',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the tasks creation link redirects to tasks creation route
     *
     * @return void
     */
    public function testClickCreateTask()
    {
        $this->auth->logIn();
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer une nouvelle tâche');
        $this->assertSame(
            '/tasks/create',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the users list link redirects to users list route
     *
     * @return void
     */
    public function testClickListUsers()
    {
        $this->auth->logIn('ROLE_ADMIN');
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Liste des utilisateurs');
        $this->assertSame(
            '/users',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the users creation link redirects to users creation route
     *
     * @return void
     */
    public function testClickCreateUsers()
    {
        $this->auth->logIn('ROLE_ADMIN');
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer un utilisateur');
        $this->assertSame(
            '/users/create',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }

    /**
     * Assert that clicking on the disconnect link redirects to logout route
     *
     * @return void
     */
    public function testClickDisconnect()
    {
        $this->auth->logIn();
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Déconnexion');
        $this->assertSame(
            '/logout',
            $this->browserKit->getRelativeUri(
                $crawler->getUri()
            )
        );
    }


}