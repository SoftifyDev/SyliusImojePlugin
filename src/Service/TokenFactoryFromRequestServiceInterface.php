<?php

declare(strict_types=1);

namespace Softify\SyliusImojePlugin\Service;

use Payum\Core\Security\TokenInterface;
use Symfony\Component\HttpFoundation\Request;

interface TokenFactoryFromRequestServiceInterface
{
    public function create(Request $request): ?TokenInterface;
}
