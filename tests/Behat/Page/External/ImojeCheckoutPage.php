<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Page\External;

use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Softify\SyliusImojePlugin\Api\ApiInterface;

class ImojeCheckoutPage extends Page implements ImojeCheckoutPageInterface
{
    private RepositoryInterface $securityTokenRepository;

    public function __construct(
        Session $session,
        $parameters,
        RepositoryInterface $securityTokenRepository
    ) {
        parent::__construct($session, $parameters);
        $this->securityTokenRepository = $securityTokenRepository;
    }

    public function pay(): void
    {
        $this->getDriver()->visit($this->findToken()->getTargetUrl());
    }

    public function cancel(): void
    {
        $this->getDriver()->visit($this->findToken()->getTargetUrl());
    }

    public function notify(): void
    {
        $this->getDriver()->visit('/payment/notify/unsafe/imoje');
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return ApiInterface::URL_SANDBOX;
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $tokens = $this->securityTokenRepository->findAll();

        /** @var TokenInterface $token */
        foreach ($tokens as $token) {
            if (strpos($token->getTargetUrl(), $type)) {
                return $token;
            }
        }

        throw new \RuntimeException(sprintf('Cannot find %s token, check if you are after proper checkout steps', $type));
    }
}
