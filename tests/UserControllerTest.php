<?php

namespace App\Tests;

use App\Entity\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional testing : Managing Users account
 */
class UserControllerTest extends WebTestCase
{

   /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     * Working with doctrine repositories
     * set up a valid connection to DB by through booting the kernel
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Testing profil Page Requesting
     */
    public function testProfileDetailsPage()
    {
        // simulate http authentification in a Functional Test
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        $client->request('GET', '/member/profile-details');

        $this->assertcontains('Nom d\'utilisateur :', $client->getResponse()->getContent());
    }

    /**
     * Testing User profile edition
     */
    public function testEditUserProfile()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneByEmail('admin@admin.com');
        ;

        $crawler = $client->request('GET', '/member/profile/'. $user->getId());

        $form = $crawler->selectButton('Valider')->form();

        $form['profile[nickName]'] = 'User_007';
        $form['profile[email]'] = 'admin@admin.com';
        ;

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();


        $this->assertcontains('Votre profil a été mis à jour.', $client->getResponse()->getContent());
    }
}
