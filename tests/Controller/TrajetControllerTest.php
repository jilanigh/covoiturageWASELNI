<?php

namespace App\Tests\Controller;

use App\Entity\Trajet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TrajetControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/trajet/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Trajet::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }



    public function testNew(): void
    {
        // Log in as a user
        $this->client->request('GET', '/login');
        $this->client->submitForm('Sign in', [
            'email' => 'me@example.com',
            'password' => 'password',
        ]);
        $this->client->followRedirect();

        // Now request the new page
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'trajet[depart]' => 'Paris',
            'trajet[arrivee]' => 'Lyon',
            'trajet[dateDepart]' => '2023-12-01 08:00:00',
            'trajet[prix]' => 50.00,
            'trajet[placeDispo]' => 3,
        ]);

        self::assertResponseRedirects('/trajet/liste');

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        // Log in as a user
        $this->client->request('GET', '/login');
        $this->client->submitForm('Sign in', [
            'email' => 'me@example.com',
            'password' => 'password',
        ]);
        $this->client->followRedirect();
       // $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setDepart('Paris');
        $fixture->setArrivee('Lyon');
        $fixture->setDateDepart(new \DateTime('2023-12-01 08:00:00'));
        $fixture->setPrix(50.00);
        $fixture->setPlaceDispo(3);

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Trajet');

        // Use assertions to check that the properties are properly displayed.
    }

public function testEdit(): void
{
    // Log in as a user
    $this->client->request('GET', '/login');
    $this->client->submitForm('Sign in', [
        'email' => 'me@example.com',
        'password' => 'password',
    ]);
    $this->client->followRedirect();

    // Create a new Trajet entity
    $fixture = new Trajet();
    $fixture->setDepart('Initial Value');
    $fixture->setArrivee('Initial Value');
    $fixture->setDateDepart(new \DateTime('2023-12-01 08:00:00'));
    $fixture->setPrix(50.00);
    $fixture->setPlaceDispo(3);

    $this->manager->persist($fixture);
    $this->manager->flush();

    // Fetch the newly created Trajet entity
    $fixture = $this->repository->find($fixture->getId());

    // Request the edit page
    $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

    // Submit the form with updated data
    $this->client->submitForm('Update', [
        'trajet[depart]' => 'Sidi thabet',
        'trajet[arrivee]' => 'geant',
        'trajet[dateDepart]' => '2024-12-01 08:00:00',
        'trajet[prix]' => 4,
        'trajet[placeDispo]' => 1,
    ]);

    self::assertResponseRedirects('/trajet/liste');

    // Fetch the updated entity from the repository
    $this->manager->refresh($fixture); // Ensure the entity is refreshed from the database
    $updatedTrajet = $this->repository->find($fixture->getId());

    // Assert that the properties have been updated
    self::assertSame('Sidi thabet', $updatedTrajet->getDepart());
    self::assertSame('geant', $updatedTrajet->getArrivee());
    self::assertSame('2024-12-01 08:00:00', $updatedTrajet->getDateDepart()->format('Y-m-d H:i:s'));
    self::assertSame(4, $updatedTrajet->getPrix());
    self::assertSame(1, (int)$updatedTrajet->getPlaceDispo());
}
public function testRemove(): void
{
    // Log in as a user
    $this->client->request('GET', '/login');
    $this->client->submitForm('Sign in', [
        'email' => 'me@example.com',
        'password' => 'password',
    ]);
    $this->client->followRedirect();

    // Create a new Trajet entity
    $fixture = new Trajet();
    $fixture->setDepart('Value');
    $fixture->setArrivee('Value');
    $fixture->setDateDepart(new \DateTime('2023-12-01 08:00:00'));
    $fixture->setPrix(50.00);
    $fixture->setPlaceDispo(3);

    $this->manager->persist($fixture);
    $this->manager->flush();

    // Fetch the newly created Trajet entity
    $fixture = $this->repository->find($fixture->getId());

    // Request the delete page
    $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
    $this->client->submitForm('Delete');

    self::assertResponseRedirects('/trajet/liste');

    // Assert that the entity has been removed
    self::assertSame(0, $this->repository->count([]));
}
}
