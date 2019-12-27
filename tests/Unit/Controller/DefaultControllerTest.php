<?php

namespace App\Tests\Unit\Controller;

use App\Entity\User;
use App\Tests\AuthenticationUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Default controller unit tests
 */
class DefaultControllerTest extends WebTestCase
{
    private $client = null;
    private $auth;

    /**
     * Set up the tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->auth = new AuthenticationUtil($this->client);
    }

    /**
     * Test if accessing to root without be logged in redirects to login route
     *
     * @return void
     */
    public function testIndexRedirection()
    {

        // test redirection to login page
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(true, $this->client->getResponse()->isRedirect('/login'));

        // test response after redirection
        $this->client->request('GET', '/');
        $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    /**
     * Test if accessing to root being logged in don't redirect user
     *
     * @return void
     */
    public function testIndexContent()
    {
        $this->auth->logIn();
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}
