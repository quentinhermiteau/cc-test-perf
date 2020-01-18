<?php

namespace App\Tests;

use App\Entity\Trick;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional testing : Managing tricks
 */
class TrickControllerTest extends WebTestCase
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
     * Testing Home Page Requesting
     */
    public function testIndexHomePage()
    {
        // simulate http authentification in a Functional Test
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('.fa-arrow-down')->count());
    }

    /**
     * Testing Trick creation page
     */
    public function testNewTrick()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        
        $crawler = $client->request('GET', '/member/new');

        

        $form = $crawler->selectButton('Valider')->form();


        $form['trick[name]'] = 'Trick 66';
        $form['trick[description]'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla condimentum ipsum ut eleifend sollicitudin. Praesent euismod nulla id faucibus condimentum. Aliquam eget enim ornare, faucibus libero pellentesque, vehicula nunc. Vestibulum interdum dignissim viverra. Integer nisi purus, consectetur vestibulum iaculis et, egestas ac lorem. Ut sollicitudin mauris pellentesque, commodo magna in, interdum quam. Quisque venenatis auctor nibh vel venenatis.';

        $form['trick[niveau]'] = 1;

        $form['trick[trick_group]'] = 1;

        // TO COMPLETE : Test insertion of new file//


        $crawler = $client->submit($form);
        $this->assertTrue(
            $client->getResponse()->isRedirect('/')
        );
    }

    /**
     * Testing Trick details page
     */
    public function testShowTrick()
    {
        $client = static::createClient();
        $tricks = $this->entityManager
            ->getRepository(Trick::class)
            ->findAll();
        ;

        $trick = $tricks[0];
        $crawler = $client->request('GET', '/'.$trick->getSlugName().'-'.$trick->getId());

        // var_dump($client->getResponse()->getContent());
        // die;

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Description")')->count());
    }

    /**
     * Testing Trick edit page
     */
    public function testEditTrick()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        $tricks = $this->entityManager
            ->getRepository(Trick::class)
            ->findAll();
        ;

        $trick = $tricks[0];

        $crawler = $client->request('GET', '/member/'. $trick->getId() .'/edit');

        $form = $crawler->selectButton('Valider')->form();

        $form['trick[name]'] = 'Trick 77';
        $form['trick[description]'] = 'Pellentesque sit amet eros at metus iaculis gravida. In sodales felis vel dolor pulvinar porttitor ut fermentum arcu. Integer metus est, viverra eget augue eget, hendrerit tempor turpis. In tellus neque, vehicula at quam ut, eleifend cursus sapien. Suspendisse consectetur et tellus in scelerisque. Morbi id lectus congue erat rhoncus accumsan. Curabitur sit amet augue lacus. Aliquam at bibendum velit. Nunc tortor magna, blandit posuere elit eu, aliquet fringilla lacus. Nam at volutpat est.';
        $form['trick[niveau]'] = 2;
        $form['trick[trick_group]'] = 2
      ;

        $crawler = $client->submit($form);

        $this->assertTrue(
          $client->getResponse()->isRedirect('/')
        );
    }

    /**
     * Testing Trick delete link
     */
    public function testDeleteTrick()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);
        $tricks = $this->entityManager
            ->getRepository(Trick::class)
            ->findAll();
        ;

        $trick = $tricks[0];

        $client->request('DELETE', '/member/'. $trick->getId() .'/delete');

        $this->assertTrue(
          $client->getResponse()->isRedirect('/')
        );
    }

    /**
     * Testing Tricks ajax request
     */
    public function testTrickAjaxRequest()
    {
        $client = static::createClient();
        $crawler = $client->xmlHttpRequest('POST', '/ajax/', ['first' => 4]);
      
        $this->assertGreaterThan(
          0,
          $crawler->filter('#trash-icon')->count()
        );
    }

    /**
     * Testing comments ajax request
     */
    public function testCommentAjaxRequest()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'Pass_1234',
        ]);

        $tricks = $this->entityManager
            ->getRepository(Trick::class)
            ->findAll();
        ;
      
        $trick = $tricks[0];
      

        $crawler = $client->xmlHttpRequest('POST', '/new_comments/'.$trick->getId());
      
        $this->assertGreaterThan(
          0,
          $crawler->filter('html:contains("à écrit le")')->count()
        );
    }
}
