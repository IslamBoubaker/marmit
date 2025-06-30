<?php

namespace App\Test\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecipeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RecipeRepository $repository;
    private string $path = '/recipe/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Recipe::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recipe index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'recipe[name]' => 'Testing',
            'recipe[description]' => 'Testing',
            'recipe[createdAt]' => 'Testing',
            'recipe[isFavorite]' => 'Testing',
            'recipe[time]' => 'Testing',
            'recipe[nbPeople]' => 'Testing',
            'recipe[difficulty]' => 'Testing',
            'recipe[price]' => 'Testing',
            'recipe[updated_at]' => 'Testing',
            'recipe[ingredients]' => 'Testing',
        ]);

        self::assertResponseRedirects('/recipe/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsFavorite('My Title');
        $fixture->setTime('My Title');
        $fixture->setNbPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setPrice('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setIngredients('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Recipe');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsFavorite('My Title');
        $fixture->setTime('My Title');
        $fixture->setNbPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setPrice('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setIngredients('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'recipe[name]' => 'Something New',
            'recipe[description]' => 'Something New',
            'recipe[createdAt]' => 'Something New',
            'recipe[isFavorite]' => 'Something New',
            'recipe[time]' => 'Something New',
            'recipe[nbPeople]' => 'Something New',
            'recipe[difficulty]' => 'Something New',
            'recipe[price]' => 'Something New',
            'recipe[updated_at]' => 'Something New',
            'recipe[ingredients]' => 'Something New',
        ]);

        self::assertResponseRedirects('/recipe/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getIsFavorite());
        self::assertSame('Something New', $fixture[0]->getTime());
        self::assertSame('Something New', $fixture[0]->getNbPeople());
        self::assertSame('Something New', $fixture[0]->getDifficulty());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getIngredients());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Recipe();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setIsFavorite('My Title');
        $fixture->setTime('My Title');
        $fixture->setNbPeople('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setPrice('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setIngredients('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/recipe/');
    }
}
