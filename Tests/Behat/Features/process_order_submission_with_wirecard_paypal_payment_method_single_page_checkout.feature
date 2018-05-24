@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroWirecardBundle:WireCardPaymentFixture.yml
@ticket-BB-13976

Feature: Process order submission with WireCard PayPal payment method single page checkout
  In order to purchase goods using WireCard - Paypal payment system using single page checkout
  As a Customer
  I want to enter and complete checkout without registration with payment via WireCard

  Scenario: Create different window session
    Given sessions active:
      | Admin | first_session  |
      | User  | second_session |

  Scenario: Create new  WireCard Integration
    Given I proceed as the Admin
    And I login as administrator
    When I go to System/Integrations/Manage Integrations
    And I click "Create Integration"
    And I select "Wirecard Seamless Checkout" from "Type"
    And I fill "WireCardForm" with:
      | Name                          | WireCard              |
      | Label                         | WireCard              |
      | Credit Card Label             | WireCardCreditCard    |
      | Credit Card Short Label       | WCC                   |
      | PayPal Label                  | WireCardPayPal        |
      | PayPal Short Label            | WCPL                  |
      | SEPA Direct Debit Label       | WireCardSEPA          |
      | SEPA Direct Debit Short Label | WCSEPA                |
      | Customer Id                   | 123                   |
      | Shop Id                       | 123                   |
      | Secret                        | secredWord123         |
    And I save and close form
    Then I should see "Integration saved" flash message
    And I should see Wirecard Seamless Checkout in grid

  Scenario: Create new Payment Rule for WireCard integration with WireCard PayPal payment method
    Given I go to System/Payment Rules
    And I click "Create Payment Rule"
    And I check "Enabled"
    And I fill in "Name" with "WireCardPayPal"
    And I fill in "Sort Order" with "1"
    And I select "€" from "Currency"
    And I select "WireCard - PayPal" from "Method"
    And I press "Add Method Button"
    And I save and close form
    Then I should see "Payment rule has been saved" flash message

  Scenario: Enable SinglePage checkout
    Given go to System/Workflows
    When I click "Activate" on row "Single Page Checkout" in grid
    And I click "Activate"
    Then I should see "Workflow activated" flash message

  Scenario: Successful order payment with WireCard PayPal payment method
    Given I proceed as the User
    And There are products in the system available for order
    And There is EUR currency in the system configuration
    When I signed in as AmandaRCole@example.org on the store frontend
    And I open page with shopping list List 1
    And I click "Create Order"
    And I select "Fifth avenue, 10115 Berlin, Germany" from "Select Billing Address"
    And I select "Fifth avenue, 10115 Berlin, Germany" from "Select Shipping Address"
    And I check "Flat Rate" on the checkout page
    And I click "Submit Order"
    Then I see the "Thank You" page with "Thank You For Your Purchase!" title
    When I proceed as the Admin
    And I go to Sales/Orders
    Then I should see Paid in full in grid
