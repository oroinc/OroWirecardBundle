@regression
@fixture-OroFlatRateShippingBundle:FlatRateIntegration.yml
@fixture-OroWirecardBundle:WireCardPaymentFixture.yml
@ticket-BB-13976

Feature: Process order submission with WireCard PayPal payment method guest checkout
  In order to purchase goods using WireCard - Paypal payment system
  As a Guest customer
  I want to enter and complete checkout without registration with payment via WireCard

  Scenario: Feature Background
    Given sessions active:
      | Admin | first_session  |
      | Guest | second_session |

  Scenario: Create new  WireCard Integration
    Given I proceed as the Admin
    And I login as administrator
    When I go to System/Integrations/Manage Integrations
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
    When I save and close form
    Then I should see "Payment rule has been saved" flash message

  Scenario: Enable guest shopping list setting
    Given I go to System/ Configuration
    And I follow "Commerce/Sales/Shopping List" on configuration sidebar
    And uncheck "Use default" for "Enable guest shopping list" field
    And I check "Enable guest shopping list"
    When I save form
    Then I should see "Configuration saved" flash message
    And the "Enable guest shopping list" checkbox should be checked

  Scenario: Enable guest checkout setting
    Given I follow "Commerce/Sales/Checkout" on configuration sidebar
    And uncheck "Use default" for "Enable Guest Checkout" field
    And I check "Enable Guest Checkout"
    When I save form
    Then the "Enable Guest Checkout" checkbox should be checked

  Scenario: Create Shopping List as unauthorized user
    Given I proceed as the Guest
    And There is EUR currency in the system configuration
    And I am on homepage
    And type "SKU123" in "search"
    And I click "Search Button"
    And I click "product1"
    And I click "Add to Shopping List"
    And I should see "Product has been added to" flash message
    When I click "Shopping List"
    Then I should see "product1"

  Scenario: Successful order payment with WireCard PayPal payment method
    Given I click "View Details Link"
    And I click "Create Order"
    And I click "Continue as a Guest"
    When I fill form with:
      | First Name      | Tester1         |
      | Last Name       | Testerson       |
      | Email           | tester@test.com |
      | Street          | Fifth avenue    |
      | City            | Berlin          |
      | Country         | Germany         |
      | State           | Berlin          |
      | Zip/Postal Code | 10115           |
    And I click "Ship to This Address"
    And I click "Continue"
    And I check "Flat Rate" on the "Shipping Method" checkout step and press Continue
    And I click "Continue"
    And I uncheck "Save my data and create an account" on the checkout page
    And I press "Submit Order"
    Then I see the "Thank You" page with "Thank You For Your Purchase!" title
    When I proceed as the Admin
    And I go to Sales/Orders
    Then I should see Paid in full in grid
