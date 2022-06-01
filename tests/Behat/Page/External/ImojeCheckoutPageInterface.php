<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Page\External;

use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use FriendsOfBehat\PageObjectExtension\Page\PageInterface;

interface ImojeCheckoutPageInterface extends PageInterface
{
    /**
     * @throws UnsupportedDriverActionException
     * @throws DriverException
     */
    public function pay(): void;

    /**
     * @throws UnsupportedDriverActionException
     * @throws DriverException
     */
    public function cancel(): void;

    /**
     * @throws UnsupportedDriverActionException
     * @throws DriverException
     */
    public function notify(): void;
}
