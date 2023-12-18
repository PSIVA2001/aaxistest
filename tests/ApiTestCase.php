<?php

declare(strict_types=1);

namespace App\Tests;

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase as ApiTestCaseCore;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class ApiTestCase extends ApiTestCaseCore
{


    /** @var  Application $application */
    protected static $application;

    /** @var  KernelBrowser $client */
    protected $client;

    /** @var  EntityManagerInterface $entityManager */
    protected $entityManager;

    protected $tokenservice;

    protected $hasher;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create');
        self::runCommand('doctrine:fixtures:load');

        $kernel = self::bootKernel();
        $this->tokenservice = $this->getContainer()->get(TokenService::class);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->hasher = $this->createMock(UserPasswordHasherInterface::class);
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    protected static function createApiClient(array $kernelOptions = [], array $defaultOptions = []): Client
    {
        return static::createClient($kernelOptions, $defaultOptions);
    }

}