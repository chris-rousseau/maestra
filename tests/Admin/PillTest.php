<?php

namespace App\Tests\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PillTest extends WebTestCase
{
    public function testAdminPillNotLoggedIn(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/pill');

        //  testing that we are redirected to another page when
        // attempting to access the page admin/pill (listing of all the pills) if not logged in
        
        $this->assertResponseStatusCodeSame(302);

    }

    

}
