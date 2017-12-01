@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroWirecardBundle:WireCardPaymentFixture.yml
Feature: Process order submission with WireCard PayPal payment method
  ToDo: BAP-16103 Add missing descriptions to the Behat features
  Scenario: Create new  WireCard Integration
    Given I login as administrator
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

  Scenario: Error from Backend when pay order with WireCard PayPal payment method
    Given There are products in the system available for order
    And There is EUR currency in the system configuration
    And I signed in as AmandaRCole@example.org on the store frontend
    When I open page with shopping list List 2
    And I press "Create Order"
    And I select "Fifth avenue, 10115 Berlin, Germany" on the "Billing Information" checkout step and press Continue
    And I select "Fifth avenue, 10115 Berlin, Germany" on the "Shipping Information" checkout step and press Continue
    And I check "Flat Rate" on the "Shipping Method" checkout step and press Continue
    And I click "Continue"
    And I press "Submit Order"
    Then I should see "We were unable to process your payment. Please verify your payment information and try again." flash message

  Scenario: Successful order payment with WireCard PayPal payment method
    Given There are products in the system available for order
    And There is EUR currency in the system configuration
    When I open page with shopping list List 1
    And I press "Create Order"
    And I select "Fifth avenue, 10115 Berlin, Germany" on the "Billing Information" checkout step and press Continue
    And I select "Fifth avenue, 10115 Berlin, Germany" on the "Shipping Information" checkout step and press Continue
    And I check "Flat Rate" on the "Shipping Method" checkout step and press Continue
    And I click "Continue"
    And I press "Submit Order"
    Then I see the "Thank You" page with "Thank You For Your Purchase!" title
    And I login as administrator
    And I go to Sales/Orders
    And I should see Paid in full in grid
