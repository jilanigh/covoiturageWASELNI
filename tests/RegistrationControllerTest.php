<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();
    }

    public function testRegister(): void
    {
        // Register a new user
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Register');

        $this->client->submitForm('Register', [
            'registration_form[email]' => 'me@example.com',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
            'registration_form[nom]' => 'jilani', // Add a non-null value for 'nom'
            'registration_form[prenom]' => 'gharbi',
            'registration_form[age]' => 25,
            'registration_form[roles]' => 'ROLE_CHAUFFEUR',
        ]);

        // Ensure the response redirects after submitting the form
        self::assertResponseRedirects('/trajet/liste'); // Adjust the path to the actual redirect path
        $this->client->followRedirect();

        // Check if the user can go to the login page
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Log in!');

    }

    //unit Test

}