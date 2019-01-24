@regression
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroWirecardBundle:WireCardPaymentFixture.yml
@ticket-BB-13976

Feature: Process order submission with WireCard Credit Card payment method single page checkout
  In order to purchase goods using WireCard - Credit Card payment system using single page checkout
  As a Customer
  I want to enter and complete single page checkout with payment via WireCard Credit Card

  Scenario: Feature Background
    Given sessions active:
      | Admin | first_session  |
      | User  | second_session |
    And I activate "Single Page Checkout" workflow

  Scenario: Create new  WireCard Integration
    Given I proceed as the Admin
    And I login as administrator
    And I go to System/Integrations/Manage Integrations
    And I click "Create Integration"
    And I select "Wirecard Seamless Checkout" from "Type"
    And I fill "WireCardForm" with:
      | Name                          | WireCard           |
      | Label                         | WireCard           |
      | Credit Card Label             | WireCardCreditCard |
      | Credit Card Short Label       | WCC                |
      | PayPal Label                  | WireCardPayPal     |
      | PayPal Short Label            | WCPL               |
      | SEPA Direct Debit Label       | WireCardSEPA       |
      | SEPA Direct Debit Short Label | WCSEPA             |
      | Customer Id                   | 123                |
      | Shop Id                       | 123                |
      | Secret                        | secredWord123      |
    When I save and close form
    Then I should see "Integration saved" flash message
    And I should see Wirecard Seamless Checkout in grid

  Scenario: Create new Payment Rule for WireCard integration with WireCard Credit Card payment method
    Given I go to System/Payment Rules
    And I click "Create Payment Rule"
    And I check "Enabled"
    And I fill in "Name" with "WireCardCreditCard"
    And I fill in "Sort Order" with "1"
    And I select "€" from "Currency"
    And I select "WireCard - Credit Card" from "Method"
    And I press "Add Method Button"
    When I save and close form
    Then I should see "Payment rule has been saved" flash message

  Scenario: Validation error should not appear if expiration date is valid
    Given I proceed as the User
    And There are products in the system available for order
    And There is EUR currency in the system configuration
    And I signed in as AmandaRCole@example.org on the store frontend
    And I open page with shopping list List 1
    And I click "Create Order"
    When I fill "WireCardCreditCardForm" with:
      | Month | 10 |
    Then I should see "Invalid Expiration date"
    When I fill "WireCardCreditCardForm" with:
      | Year | 2029 |
    Then I should not see "Invalid Expiration date"

  Scenario: Successful order payment with WireCard Credit Card payment method
    Given I select "Fifth avenue, 10115 Berlin, Germany" from "Select Billing Address"
    And I select "Fifth avenue, 10115 Berlin, Germany" from "Select Shipping Address"
    And I check "Flat Rate" on the checkout page
    And I fill "WireCardCreditCardForm" with:
      | Cardholder Name    | John Doe         |
      | Credit Card Number | 5105105105105100 |
      | Month              | 11               |
      | Year               | 2027             |
      | CVV                | 123              |
    When I click "Submit Order"
    Then I see the "Thank You" page with "Thank You For Your Purchase!" title

  Scenario: Check order status in admin panel
    Given I proceed as the Admin
    When I go to Sales/Orders
    Then I should see Paid in full in grid
