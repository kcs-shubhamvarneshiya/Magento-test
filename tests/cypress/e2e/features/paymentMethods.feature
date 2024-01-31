Feature: Payment Methods

    Retail, Trade, or Wholesale users can place an order with the different payment methods.

    #--------------------------------------------------------------------------------------------------

    @all @sanity @paymentMethods @retail @us @uk @testcase_03
    Scenario: The retail user should be able to place an order for a product with credit card

        Given user is on the login page
        When 'Retail' user do login into the website
            And user search 'JN5112' 
            And user press enter key
            And user click on the 'Talia Large Chandelier' product from the products list
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Credit/Debit Card |
                | UK      | Credit Card       |
            And 'Retail' user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Name               | Test Account     |  
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 1234             |
                | UK      | Card Number        | 4242424242424242 |
                | UK      | Expiration Month   | 07 - July        |
                | UK      | Expiration Year    | 2027             |
                | UK      | CVV                | 123              |
            And user click on the place order button
        Then order of product should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |
    
    #--------------------------------------------------------------------------------------------------

    @all @paymentMethods @trade @us @uk @testcase_04 
    Scenario: The trade user should be able to place an order for a product with credit card 

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | JN5112     |
                | UK      | EU-TOB5002 |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Polished Nickel              |
                | UK      | Shade   | 10.2cm x 14cm x 15.2cm Linen | 
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod        |
                | US      | Standard Shipping     |
                | UK      | Standard Shipping     |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Credit/Debit Card |
                | UK      | Credit Card       |
            And 'Trade' user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Name               | Test Account     |  
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 1234             |
                | UK      | Card Number        | 4242424242424242 |
                | UK      | Expiration Month   | 07 - July        |
                | UK      | Expiration Year    | 2027             |
                | UK      | CVV                | 123              |
            And user click on the place order button
        Then order of product should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @sanity @paymentMethods @wholesale @us @testcase_05
    Scenario: The wholesale user should be able to place an order for a product with credit card

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | JN5112     |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And wholesale user click on the add to cart button 
            And wholesale user click on the begin checkout button from the mini cart
            And wholesale user select the following shipping method
                | country | shippingMethod    |
                | US      | Ship When Ready   |
            And wholesale user click on the continue to payment button
            And wholesale user select the following payment method
                | country | paymentMethod     |
                | US      | Credit Card       |
            And wholesale user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 1234             |
                | US      | PO Number          | H71              |
            And wholesale user click on the place order button
        Then order of product for wholesale user should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |

    #--------------------------------------------------------------------------------------------------
    
    @all @paymentMethods @retail @us @testcase_06 @skip
    Scenario: The retail user should be able to place an order for a product with affirm payment method

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Pay over time     |
            And user click on the place order button for affirm
            And user enter '9124471008' affirm mobile number 
            And user click on the affirm continue button
            And user enter '123456' affirm mobile pin
            And user select '3 months' affirm plan
            And user click on the affirm plan continue button
            And user click on the I agree checkbox of affirm auto-pay
            And user click on the affirm auto-pay confirm button
        Then order of product with affirm payment method should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |

    #--------------------------------------------------------------------------------------------------
    
    @all @paymentMethods @wholesale @us @testcase_07
    Scenario: The wholesale user should be able to place an order for a product with wholesale terms

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And wholesale user click on the add to cart button
            And wholesale user click on the begin checkout button from the mini cart
            And wholesale user select the following shipping method
                | country | shippingMethod    |
                | US      | Ship When Ready   |
            And wholesale user click on the continue to payment button
            And wholesale user select the following payment method
                | country | paymentMethod   |
                | US      | Wholesale Terms |
            And wholesale user enters the following credit card or po details:
                | country | field     | value |
                | US      | PO Number | H71V  |
            And wholesale user click on the place order button
        Then order of product for wholesale user should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @paymentMethods @trade @us @testcase_08
    Scenario: The trade user should be able to place an order for a product with net 30

        Given user is on the login page
        When 'Trade' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod       |
                | US      | Standard Shipping    |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Net 30            |
            And user click on the place order button for net 30
        Then order of product with net 30 payment method should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |

    #--------------------------------------------------------------------------------------------------
    
    @all @paymentMethods @user @us @testcase_09
    Scenario: The guest user should be able to place an order for a product with credit card

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user enter the following shipping details:
                | country | field          | value                    |
                | US      | Email          | test.account@yopmail.com |
                | US      | First Name     | Test                     |
                | US      | Last Name      | Account                  |
                | US      | Address Line 1 | 2808 18th St S           |
                | US      | Country        | United States            |
                | US      | City           | Homewood                 |
                | US      | State          | Alabama                  |
                | US      | Zip Code       | 35209-2510               |
                | US      | Phone Number   | 2058475330               |
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Credit/Debit Card |
            And 'Guest' user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Name               | Test Account     |  
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 1234             |
            And user click on the place order button
        Then order of product should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |

    #--------------------------------------------------------------------------------------------------

    @all @paymentMethods @retail @us @uk @testcase_10
    Scenario: The new retail user should be able to place an order for a product with credit card
    
        Given user is on the create new account page
        When the retail user creates a new account with the following details:
                | field      | value                    |
                | First Name | Barbara                  |
                | Last Name  | Hepworth                 |
                | Email      | test.account@yopmail.com |
                | Password   | sculpture@stIves123      |
            And new user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
            And new user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
            And new user click on the add to cart button
            And new user click on the begin checkout button from the mini cart
            And new user enter the following shipping details:
                | country | field          | value                    |
                | US      | Address Line 1 | 2808 18th St S           |
                | US      | Country        | United States            |
                | US      | City           | Homewood                 |
                | US      | State          | Alabama                  |
                | US      | Zip Code       | 35209-2510               |
                | US      | Phone Number   | 2058475330               |
                | UK      | Address Line 1 | 52 Southend Avenue       |
                | UK      | Country        | United Kingdom           |
                | UK      | City           | Blackwater               |
                | UK      | State          | Alabama                  |
                | UK      | Zip Code       | PO30 1FL                 |
                | UK      | Phone Number   | 070 8919 6791            |
            And new user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
            And new user click on the continue to payment button
            And new user select the following payment method
                | country | paymentMethod     |
                | US      | Credit/Debit Card |
                | UK      | Credit Card       |
            And new retail user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Name               | Test Account     |  
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 1234             |
                | UK      | Card Number        | 4242424242424242 |
                | UK      | Expiration Month   | 07 - July        |
                | UK      | Expiration Year    | 2027             |
                | UK      | CVV                | 123              |
            And user click on the place order button
        Then order of product should get placed with the following success message:
                | successMessage              |
                | Thank you for your purchase |

    #--------------------------------------------------------------------------------------------------

    @all @paymentMethods @retail @us @uk @testcase_205
    Scenario: The retail user should not be able to place an order for a product with wrong cvv 

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
            And user click on the continue to payment button
            And user select the following payment method
                | country | paymentMethod     |
                | US      | Credit/Debit Card |
                | UK      | Credit Card       |
            And 'Retail' user enters the following credit card or po details:
                | country | field              | value            |
                | US      | Name               | Test Account     |  
                | US      | Card Number        | 370000000000002  |
                | US      | Expiration Month   | 07 - July        |
                | US      | Expiration Year    | 2027             |
                | US      | CVV                | 901              |
                | UK      | Card Number        | 4242424242424242 |
                | UK      | Expiration Month   | 07 - July        |
                | UK      | Expiration Year    | 2027             |
                | UK      | CVV                | 90               |
            And user click on the place order button
        Then the following validation message should be displayed on the payment page
                | country | message                                               |
                | US      | Please enter a valid credit card verification number. |
                | UK      | Please enter a valid number in this field.            |