<?php

declare(strict_types=1);

namespace Tests\Softify\SyliusImojePlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\Softify\SyliusImojePlugin\Behat\Mocker\ImojeApiMocker;
use Tests\Softify\SyliusImojePlugin\Behat\Page\External\ImojeCheckoutPageInterface;

final class ImojeContext implements Context
{

    private ImojeApiMocker $imojeApiMocker;
    private ShowPageInterface $orderDetails;
    private CompletePageInterface $summaryPage;
    private ImojeCheckoutPageInterface $imojeCheckoutPage;

    public function __construct(
        ImojeApiMocker $imojeApiMocker,
        ShowPageInterface $orderDetails,
        CompletePageInterface $summaryPage,
        ImojeCheckoutPageInterface $imojeCheckoutPage
    ) {
        $this->orderDetails = $orderDetails;
        $this->summaryPage = $summaryPage;
        $this->imojeCheckoutPage = $imojeCheckoutPage;
        $this->imojeApiMocker = $imojeApiMocker;
    }

    /**
     * @When I confirm my order with Imoje payment
     * @Given I have confirmed my order with Imoje payment
     */
    public function iConfirmMyOrderWithImojePayment(): void
    {
        $this->imojeApiMocker->mockApiSuccessfulPaymentResponse(
            function () {
                $this->summaryPage->confirmOrder();
            }
        );
    }

    /**
     * @When Imoje payment notify with correct signature
     */
    public function ImojePaymentNotifyWithCorrectSignature(): void
    {
        $this->imojeApiMocker->notifyPayment(
            function () {
                $this->imojeCheckoutPage->notify();
            }
        , true, true);
    }

    /**
     * @When Imoje payment notify with missing token
     */
    public function ImojePaymentNotifyWithMissingToken(): void
    {
        $this->imojeApiMocker->notifyPayment(
            function () {
                $this->imojeCheckoutPage->notify();
            }
        , false, true);
    }

    /**
     * @When Imoje payment notify with incorrect signature
     */
    public function ImojePaymentNotifyWithIncorrectSignature(): void
    {
        $this->imojeApiMocker->notifyPayment(
            function () {
                $this->imojeCheckoutPage->notify();
            }
            , true, false);
    }

    /**
     * @When I sign in to Imoje and pay successfully
     */
    public function iSignInToImojeAndPaySuccessfully(): void
    {
        $this->imojeApiMocker->completedPayment(
            function () {
                $this->imojeCheckoutPage->pay();
            }
        );
    }

    /**
     * @When I cancel my Imoje payment
     * @Given I have cancelled Imoje payment
     */
    public function iCancelMyImojePayment(): void
    {
        $this->imojeApiMocker->canceledPayment(
            function () {
                $this->imojeCheckoutPage->cancel();
            }
        );
    }

    /**
     * @When I try to pay again with Imoje payment
     */
    public function iTryToPayAgainWithImojePayment(): void
    {
        $this->imojeApiMocker->mockApiSuccessfulPaymentResponse(
            function () {
                $this->orderDetails->pay();
            }
        );
    }
}
