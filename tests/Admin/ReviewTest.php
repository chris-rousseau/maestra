<?php

namespace App\Tests\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
    public function testAdminReviewNotLoggedIn(): void
    {
        // simulating access to the web page like a browser would
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/pill/review/list');

        // testing that we are redirected to another page when
        // attempting to access the page admin/pill (listing of all the pills) if not logged in
        $this->assertResponseStatusCodeSame(302);
    }

    public function testAdminReviewUnAuthorizedUser()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        // findOneByEmail ==> findOneBy(['email' => 'demo1@oclock.io'])
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('random@gmail.com');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the pills (Error 403)
        $crawler = $client->request('GET', '/admin/pill/review/list');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminReviewAuthorizedModerator()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        // findOneByEmail ==> findOneBy(['email' => 'demo1@oclock.io'])
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@boubou.fr');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the pills (Error 403)
        $crawler = $client->request('GET', '/admin/pill/review/list');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminReviewAuthorized()
    {
        $client = static::createClient();

        // Simulating a connexion with a role admin (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@gmail.com');

        $client->loginUser($user);

        // Testing and asserting that as an ADMIN, we have access to the page
        $client->request('GET', '/admin/pill/review/list');
        $this->assertResponseIsSuccessful();
    }

    public function testDisplayOneReview()
    {
        $client = static::createClient();

        // Simulating a connexion with a role administrator (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('chris@gmail.com');
        $client->loginUser($user);

        // Testing and asserting if the click on "Passer admin" works
        $crawler = $client->request('GET', '/admin/pill/review/list');
        $crawler->selectLink('Voir')->link();

        $this->assertResponseStatusCodeSame(200);
    }
}
