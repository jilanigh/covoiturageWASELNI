<?php

namespace App\Tests\Controller;

use App\Entity\Voiture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class VoitureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/voiture/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Voiture::class);

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
        self::assertPageTitleContains('Voiture index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'voiture[marque]' => 'Testing',
            'voiture[modele]' => 'Testing',
            'voiture[anneeFabrication]' => 'Testing',
            'voiture[couleur]' => 'Testing',
            'voiture[immat]' => 'Testing',
            'voiture[trajet]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('My Title');
        $fixture->setModele('My Title');
        $fixture->setAnneeFabrication('My Title');
        $fixture->setCouleur('My Title');
        $fixture->setImmat('My Title');
        $fixture->setTrajet('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Voiture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('Value');
        $fixture->setModele('Value');
        $fixture->setAnneeFabrication('Value');
        $fixture->setCouleur('Value');
        $fixture->setImmat('Value');
        $fixture->setTrajet('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'voiture[marque]' => 'Something New',
            'voiture[modele]' => 'Something New',
            'voiture[anneeFabrication]' => 'Something New',
            'voiture[couleur]' => 'Something New',
            'voiture[immat]' => 'Something New',
            'voiture[trajet]' => 'Something New',
        ]);

        self::assertResponseRedirects('/voiture/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMarque());
        self::assertSame('Something New', $fixture[0]->getModele());
        self::assertSame('Something New', $fixture[0]->getAnneeFabrication());
        self::assertSame('Something New', $fixture[0]->getCouleur());
        self::assertSame('Something New', $fixture[0]->getImmat());
        self::assertSame('Something New', $fixture[0]->getTrajet());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Voiture();
        $fixture->setMarque('Value');
        $fixture->setModele('Value');
        $fixture->setAnneeFabrication('Value');
        $fixture->setCouleur('Value');
        $fixture->setImmat('Value');
        $fixture->setTrajet('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/voiture/');
        self::assertSame(0, $this->repository->count([]));
    }
}
