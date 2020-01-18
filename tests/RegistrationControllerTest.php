<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
   * Functional testing : Registration
   */
class RegistrationControllerTest extends WebTestCase
{
    /**
     * Testing registration restrictions
     * No password given
     * Asserting Error
     */
    public function testEmptyPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['user[nickName]'] = 'JoJo';
        $form['user[email]'] = 'jojo@jojo.fr';
        $form['user[plainPassword][first]'] = '';
        $form['user[plainPassword][second]'] = '';

        $crawler = $client->submit($form);

        $this->assertEquals(
            1,
            $crawler->filter('body:contains("La valeur ne peut pas être vide")')->count()
        );
    }

    /**
     * Testing registration restrictions
     * Not respecting password Regex
     * Asserting Error
     */
    public function testPasswordRegex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['user[nickName]'] = 'JoJo';
        $form['user[email]'] = 'jojo@jojo.fr';
        $form['user[plainPassword][first]'] = '1234';
        $form['user[plainPassword][second]'] = '1234';

        $crawler = $client->submit($form);

        $this->assertEquals(
            1,
            $crawler->filter('body:contains("Votre mot de passe doit contenir: un chiffre, un majuscule, un minuscule.")')->count()
        );
    }

    /**
     * Testing registration restrictions
     * Create a valid account
     * Asserting redirection to login page
     */
    public function testValidPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['user[nickName]'] = 'JoJo';
        $form['user[email]'] = 'jojo@jojo.fr';
        $form['user[plainPassword][first]'] = 'JoJo1234';
        $form['user[plainPassword][second]'] = 'JoJo1234';

        $crawler = $client->submit($form);

        $this->assertTrue(
          $client->getResponse()->isRedirect('/login')
        );
    }

    
    /**
     * Testing registration restrictions
     * Use existing mail
     * Asserting Error
     */
    public function testUniqueEmail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $form = $crawler->selectButton('Envoyer')->form();

        $form['user[nickName]'] = 'admin';
        $form['user[email]'] = 'admin@admin.com';
        $form['user[plainPassword][first]'] = 'Admin1234';
        $form['user[plainPassword][second]'] = 'Admin1234';

        $crawler = $client->submit($form);

        $this->assertEquals(
          1,
          $crawler->filter('body:contains("Cet Email est deja utilisé")')->count()
        );
    }
}
