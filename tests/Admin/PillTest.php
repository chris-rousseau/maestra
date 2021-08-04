<?php

namespace App\Tests\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PillTest extends WebTestCase
{
    public function testAdminPillNotLoggedIn(): void
    {
        // simulating access to the web page like a browser would
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/pill');

        // testing that we are redirected to another page when
        // attempting to access the page admin/pill (listing of all the pills) if not logged in
        $this->assertResponseStatusCodeSame(302);
    }

    public function testAdminPillUnAuthorizedUser()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        // findOneByEmail ==> findOneBy(['email' => 'demo1@oclock.io'])
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('random@gmail.com');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the pills (Error 403)
        $crawler = $client->request('GET', '/admin/pill');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminPillUnAuthorized()
    {
        $client = static::createClient();

        // Simulating a connexion with a role moderator (ROLE_MODERATOR)        
        // findOneByEmail ==> findOneBy(['email' => 'demo1@oclock.io'])
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@boubou.fr');
        $client->loginUser($user);

        // Testing and asserting that a user with a ROLE_MODERATOR doesn't have
        // access to the admin page listing the pills (Error 403)
        $crawler = $client->request('GET', '/admin/pill');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAdminPillAuthorized()
    {
        $client = static::createClient();

        // Simulating a connexion with a role admin (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@gmail.com');

        $client->loginUser($user);

        // Testing and asserting that as an ADMIN, we have access to the page
        $client->request('GET', '/admin/pill');
        $this->assertResponseIsSuccessful();
    }

    public function testPillAdd(): void
    {
        $client = static::createClient();

        // Simulating a connexion with a role admin (ROLE_ADMIN)        
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('lulu@gmail.com');
        $client->loginUser($user);

        // On simule l'accès au formulaire d'ajout d'une catégorie
        $client->request('GET', '/admin/pill/add');

        // On va simuler la soumission du formulaire (Clic sur le bouton "Valider")
        $crawler = $client->submitForm('Ajouter une pilule', [
            'pill[name]' => 'Pilule Testing',
            'pill[description]' => 'Test2',
            'pill[reimbursed]' => 0,
            'pill[posology]' => '21jours',
            'pill[generation]' => 1,
            'pill[interruption]' => 1,
            'pill[laboratory]' => 'Test2',
            'pill[delay_intake]' => 12,
        ]);

        // We check that after the submit, we are redirected to 
        // the page listing all pills /admin/pill
        $this->assertResponseRedirects('/admin/pill');
    }
}
