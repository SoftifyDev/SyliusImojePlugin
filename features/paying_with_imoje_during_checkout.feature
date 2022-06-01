@paying_for_order_with_imoje
Feature: Paying with Imoje during checkout
  In order to buy products
  As a Customer
  I want to be able to pay with Imoje

  Background:
    Given the store operates on a single channel in "United States"
    And there is a user "john@softify.dev" identified by "password"
    And the store has a payment method "Imoje" with a code "imoje" and Imoje Checkout gateway
    And the store has a product "PHP T-Shirt" priced at "$19.99"
    And the store ships everywhere for free
    And I am logged in as "john@softify.dev"

  @ui
  Scenario: Successful payment
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Imoje" payment method
    When I confirm my order with Imoje payment
    And I sign in to Imoje and pay successfully
    Then I should be notified that my payment has been completed
    When Imoje payment notify with correct signature
    Then the response status code should be 200
    When Imoje payment notify with incorrect signature
    Then the response status code should be 500
    When Imoje payment notify with missing token
    Then the response status code should be 204

  @ui
  Scenario: Cancelling the payment
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Imoje" payment method
    When I confirm my order with Imoje payment
    And I cancel my Imoje payment
    Then I should be notified that my payment has been cancelled
    And I should be able to pay again

  @ui
  Scenario: Retrying the payment with success
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Imoje" payment method
    And I have confirmed my order with Imoje payment
    But I have cancelled Imoje payment
    When I try to pay again with Imoje payment
    And I sign in to Imoje and pay successfully
    Then I should be notified that my payment has been completed
    And I should see the thank you page

  @ui
  Scenario: Retrying the payment and failing
    Given I added product "PHP T-Shirt" to the cart
    And I have proceeded selecting "Imoje" payment method
    And I have confirmed my order with Imoje payment
    But I have cancelled Imoje payment
    When I try to pay again with Imoje payment
    And I cancel my Imoje payment
    Then I should be notified that my payment has been cancelled
    And I should be able to pay again
