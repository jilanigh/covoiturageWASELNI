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

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Trajet index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'trajet[depart]' => 'Testing',
            'trajet[arrivee]' => 'Testing',
            'trajet[dateDepart]' => 'Testing',
            'trajet[prix]' => 'Testing',
            'trajet[placeDispo]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setDepart('My Title');
        $fixture->setArrivee('My Title');
        $fixture->setDateDepart('My Title');
        $fixture->setPrix('My Title');
        $fixture->setPlaceDispo('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Trajet');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setDepart('Value');
        $fixture->setArrivee('Value');
        $fixture->setDateDepart('Value');
        $fixture->setPrix('Value');
        $fixture->setPlaceDispo('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'trajet[depart]' => 'Something New',
            'trajet[arrivee]' => 'Something New',
            'trajet[dateDepart]' => 'Something New',
            'trajet[prix]' => 'Something New',
            'trajet[placeDispo]' => 'Something New',
        ]);

        self::assertResponseRedirects('/trajet/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDepart());
        self::assertSame('Something New', $fixture[0]->getArrivee());
        self::assertSame('Something New', $fixture[0]->getDateDepart());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getPlaceDispo());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Trajet();
        $fixture->setDepart('Value');
        $fixture->setArrivee('Value');
        $fixture->setDateDepart('Value');
        $fixture->setPrix('Value');
        $fixture->setPlaceDispo('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/trajet/');
        self::assertSame(0, $this->repository->count([]));
    }
}
