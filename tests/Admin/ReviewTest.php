<?php

namespace App\Tests\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
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
