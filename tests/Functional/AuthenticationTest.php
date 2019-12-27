<?php

namespace App\Tests\Functional;

use App\Tests\AuthenticationUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional tests of authentication features
 */
class AuthenticationTest extends WebTestCase
{
    private $client = null;
    private $auth;

    /**
     * Set up the app for tests
     *
     * @return void
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->auth = new AuthenticationUtil($this->client);
    }

    /**
     * Assert that sending a wrong authentication form don't connect the user
     *
     * @return void
     */
    public function testSubmitWrongAuthenticationForm()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'xxx';
        $form['password'] = 'xxx';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('div.alert-danger')->count());
        $this->assertSame('Username could not be found.', $crawler->filter('div.alert-danger')->text());
    }

    /**
     * Assert that sending an authentication form connects the user
     *
     * @return void
     */
    public function testSubmitCorrectAuthenticationForm()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['username'] = 'user';
        $form['password'] = 'user';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('user', $crawler->filter('nav a.username')->text());
    }

}