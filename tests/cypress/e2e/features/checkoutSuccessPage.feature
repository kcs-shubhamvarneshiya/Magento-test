Feature: Checkout Success Page

    The user should be able to access the checkout success page.

    #--------------------------------------------------------------------------------------------------

    @all @checkoutSuccessPage @retail @us @uk @testcase_230
    Scenario: In a retail account, the product ship by date of checkout success page should be the same as displayed on the product description page

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
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
            And the product ship by date of checkout success page should be the same as displayed on the product description page

    #--------------------------------------------------------------------------------------------------
    
    @all @checkoutSuccessPage @wholesale @us @testcase_231
    Scenario: In a wholesale account, the product ship by date of checkout success page should be the same as displayed on the product description page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
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
            And the product ship by date of checkout success page should be the same as displayed on the product description page
    
    #--------------------------------------------------------------------------------------------------
    
    @all @checkoutSuccessPage @user @us @testcase_232
    Scenario: In a guest account, the product ship by date of checkout success page should be the same as displayed on the product description page

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |    
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
            And the product ship by date of checkout success page should be the same as displayed on the product description page