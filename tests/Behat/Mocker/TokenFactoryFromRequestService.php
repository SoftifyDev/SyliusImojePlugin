<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Mocker;

use Payum\Core\Security\TokenInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Softify\SyliusImojePlugin\Service\TokenFactoryFromRequestServiceInterface;

class TokenFactoryFromRequestService implements TokenFactoryFromRequestServiceInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function create(Request $request): ?TokenInterface
    {
        return $this->container->get('softify.imoje_plugin.service.token_factory')->create($request);
    }
}
