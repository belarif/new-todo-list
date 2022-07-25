<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

final class TodoListClient
{
    private KernelBrowser $_client;

    private ContainerInterface $_container;

    private Crawler $crawler;

    public function __construct(KernelBrowser $client)
    {
        $this->_client = $client;
        $this->_container = $client->getContainer();
    }

    public function getService(string $serviceId): ?object
    {
        return $this->_container->get($serviceId);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->_container;
    }

    public function createFixtureBuilder(): TodoListFixtureBuilder
    {
        return new TodoListFixtureBuilder(
            $this->_client->getContainer()
        );
    }

    public function startSession(bool $transactional): self
    {
        if ($transactional) {
            $entityManager = $this->_container->get('doctrine.orm.default_entity_manager');
            $entityManager->beginTransaction();
        }

        return $this;
    }

    public function loginUser(User $user)
    {
        $this->_client->loginUser($user);
    }

    public function getCurrentLoggedUser(): User
    {
        $security = $this->_container->get('security.token_storage');

        return $security->getToken()->getUser();
    }

    public function sendRequest(string $method, $url, $parameters = []): Response
    {
        $this->crawler = $this->_client->request($method, $url, $parameters);

        return $this->_client->getResponse();
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    public function submitForm($button)
    {
        return $this->_client->submit($button);
    }

    public function redirectTo()
    {
        return $this->_client->followRedirect();
    }
}
