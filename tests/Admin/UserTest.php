<?php

namespace App\Tests\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testConnexionBackOfficeLoggedInAdmin(): void
    {
        $client = static::createClient();
        // Simulating a connexion with a role admin (ROLE_ADMIN) 
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'chris@gmail.com']);

        $client->loginUser($testUser);

        // Testing and asserting that as an ADMIN, we have access to the page
        $client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminUserUnAuthorizedUser()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        // findOneByEmail ==> findOneBy(['email' => 'demo1@oclock.io'])
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('random@gmail.com');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the pills (Error 403)
        $crawler = $client->request('GET', '/admin/user');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUserListUnAuthorized()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@boubou.fr');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the users (Error 403)
        $crawler = $client->request('GET', '/admin/user');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testUserListAuthorized()
    {
        $client = static::createClient();

        // Simulating a connexion with a role administrator (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('chris@gmail.com');
        $client->loginUser($user);

        // Testing and asserting that as an ADMIN, we have access to the page
        $crawler = $client->request('GET', '/admin/user');
        $this->assertResponseIsSuccessful();
    }

    public function testUpgradeUserInModerator()
    {
        $client = static::createClient();

        // Simulating a connexion with a role administrator (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('chris@gmail.com');
        $client->loginUser($user);

        // Testing and asserting if the click on "Passer modérateur" works
        $crawler = $client->request('GET', '/admin/user');
        $crawler->selectLink('Passer modérateur')->link();

        $this->assertResponseIsSuccessful();
    }

    public function testUpgradeUserInAdmin()
    {
        $client = static::createClient();

        // Simulating a connexion with a role administrator (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('chris@gmail.com');
        $client->loginUser($user);

        // Testing and asserting if the click on "Passer admin" works
        $crawler = $client->request('GET', '/admin/user');
        $crawler->selectLink('Passer admin')->link();

        $this->assertResponseIsSuccessful();
    }
}
