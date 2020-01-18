<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional testing : Authentification
 */
class SecurityControllerTest extends WebTestCase
{

    /**
     * Testing authentification restrictions
     * Use valid user info
     * Asserting loggin in success
     */
    public function testValidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['_username'] = 'admin@admin.com';
        $form['_password'] = 'Pass_1234';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
       
        $this->assertEquals(
            1,
            $crawler->filter('li:contains("Nouvelle figure")')->count()
        );
    }

    /**
     * Testing authentification restrictions
     * Use non valid user info
     * Asserting loggin in error
     */
    public function testWrongCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['_username'] = 'wrong@wrong.com';
        $form['_password'] = 'mkgfg87';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
       
        $this->assertEquals(
            1,
            $crawler->filter('span:contains("Identifiants invalides.")')->count()
        );
    }
}
