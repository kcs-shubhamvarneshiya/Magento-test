Feature: Checkout Payment Page

    The user should be able to access the checkout payment page.

    #--------------------------------------------------------------------------------------------------

    @all @checkoutPaymentPage @retail @us @uk @eu @testcase_249
    Scenario: The retail user should be able to see the order summary section and its details

        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user unselect the bulb checkbox
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user collect the following details from the shopping cart page
                | label             |
                | Cart Subtotal     |
                | Shipping Charges  |
                | Tax/Vat           |
                | Merchandise Total |
            And user click on the begin checkout button from the shopping cart page
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
                | EU      | Percent           |
            And user click on the continue to payment button
        Then the order summary section should be displayed on the payment page
            And the following details should be displayed on the order summary section
                | label             |
                | Product Name      |
                | Product Price     |
                | Product Qty       |
                | Finish            |
                | Bulb Name         |
                | Bulb Price        |
                | Bulb Qty          |
                | Cart Subtotal     |
                | Shipping Charges  |
                | Tax/Vat           |
                | Merchandise Total |
    
    #--------------------------------------------------------------------------------------------------

    @all @checkoutPaymentPage @trade @us @uk @eu @testcase_250
    Scenario: The trade user should be able to see the order summary section and its details

        Given user is on the login page
        When 'Trade' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user unselect the bulb checkbox
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user collect the following details from the shopping cart page
                | label             |
                | Cart Subtotal     |
                | Shipping Charges  |
                | Tax/Vat           |
                | Merchandise Total |
            And user click on the begin checkout button from the shopping cart page
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
                | EU      | Percent           |
            And user click on the continue to payment button
        Then the order summary section should be displayed on the payment page
            And the following details should be displayed on the order summary section
                | label             |
                | Product Name      |
                | Product Price     |
                | Product Qty       |
                | Finish            |
                | Bulb Name         |
                | Bulb Price        |
                | Bulb Qty          |
                | Cart Subtotal     |
                | Shipping Charges  |
                | Tax/Vat           |
                | Merchandise Total |
    
    #--------------------------------------------------------------------------------------------------
 
    @all @checkoutPaymentPage @wholesale @us @testcase_251
    Scenario: The wholesale user should be able to see the order summary section and its details

        Given user is on the login page
        When 'wholesale' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user collect the following details from the shopping cart page
                | label             |
                | Cart Subtotal     |
                | Merchandise Total |
            And user click on the begin checkout button from the shopping cart page
            And wholesale user select the following shipping method
                | country | shippingMethod  |
                | US      | Ship When Ready |
            And user click on the continue to payment button
        Then the order summary section should be displayed on the payment page
            And the following details should be displayed on the order summary section
                | label             |
                | Product Name      |
                | Product Price     |
                | Product Qty       |
                | Finish            |
                | Cart Subtotal     |
                | Merchandise Total |
    
    #--------------------------------------------------------------------------------------------------

    @all @checkoutPaymentPage @user @us @uk @eu @testcase_252
    Scenario: The guest user should be able to see the order summary section and its details

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user unselect the bulb checkbox
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user collect the following details from the shopping cart page
                | label             |
                | Cart Subtotal     |
                | Shipping Charges  |
                | Merchandise Total |
                | Vat               |
            And user click on the begin checkout button from the shopping cart page
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
                | UK      | Email          | test.account@yopmail.com |
                | UK      | First Name     | Test                     |
                | UK      | Last Name      | Account                  |
                | UK      | Address Line 1 | 52 Southend Avenue       |
                | UK      | Country        | United Kingdom           |
                | UK      | City           | Newport                  |
                | UK      | State          | Barton                   |
                | UK      | Zip Code       | PO30 1FL                 |
                | UK      | Phone Number   | 07089196791              |
                | EU      | Email          | test.account@yopmail.com |
                | EU      | First Name     | Test                     |
                | EU      | Last Name      | Account                  |
                | EU      | Address Line 1 | 108, De Hoper North      |
                | EU      | Country        | Netherlands              |
                | EU      | City           | Amsterdam                |
                | EU      | State          | Holland                  |
                | EU      | Zip Code       | 1511 HN                  |
                | EU      | Phone Number   | 07089196791              |
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | Standard Shipping |
                | UK      | Standard Shipping |
                | EU      | Percent           |
            And user click on the continue to payment button
        Then the order summary section should be displayed on the payment page
            And the following details should be displayed on the order summary section
                | label                        |
                | Product Name                 |
                | Product Price                |
                | Product Qty                  |
                | Finish                       |
                | Bulb Name                    |
                | Bulb Price                   |
                | Bulb Qty                     |
                | Cart Subtotal                |
                | Shipping Charges             |
                | Vat                          |
                | Merchandise Total Guest User |