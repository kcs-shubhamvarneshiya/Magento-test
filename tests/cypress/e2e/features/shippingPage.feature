Feature: Shipping Page

    The user should be able to access the shipping page.

    #--------------------------------------------------------------------------------------------------
   
    @all @shippingPage @retail @us @testcase_18
    Scenario: The retail user should be able to select product with 2 day shipping method

        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl         |
                | US      | /classic-ring-chandelier-sl5812/  |
            And user select the following product variants:
                | country | variant | value                |
                | US      | Finish  | Bronze               |
                | US      | Shade   | 3" x 6.5" X 5" Linen |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod    |
                | US      | 2 day             |
            And user click on the continue to payment button
        Then user should see 'FedEx - 2 Day' shipping method in order summary section

    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @retail @us @testcase_19
    Scenario: The retail user should be able to select product with Standard Overnight shipping method

        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl         |
                | US      | /classic-ring-chandelier-sl5812/  |
            And user select the following product variants:
                | country | variant | value                |
                | US      | Finish  | Bronze               |
                | US      | Shade   | 3" x 6.5" X 5" Linen |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod     |
                | US      | Standard Overnight |
            And user click on the continue to payment button
        Then user should see 'FedEx - Standard Overnight' shipping method in order summary section
    
    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @retail @us @uk @testcase_20
    Scenario: The retail user should be able to select product with Standard Shipping method

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
        Then user should see 'Best Way - Standard Shipping' shipping method in order summary section
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shippingPage @wholesale @us @testcase_21
    Scenario: The wholesale user should be able to select product with Ship When Ready shipping method

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
                | country | shippingMethod  |
                | US      | Ship When Ready |
            And wholesale user click on the continue to payment button
        Then wholesale user should see 'Ship When Ready' shipping method in shipping section
    
    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @wholesale @us @testcase_22
    Scenario: The wholesale user should be able to select product with Request Delivery Date shipping method

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
                | country | shippingMethod        |
                | US      | Request Delivery Date |
            And wholesale user select 2 days after the current date
            And wholesale user click on the continue to payment button
        Then wholesale user should see 2 days date after the current date as shipping method in shipping section

    #--------------------------------------------------------------------------------------------------
    
    @all @shippingPage @trade @us @testcase_23
    Scenario: The trade user should be able to select product with Priority Overnight shipping method
    
        Given user is on the login page
        When 'Trade' user do login into the website
            And user clear added items from the cart    
            And user enter the following product description page url:
                | country | productDescriptionPageUrl         |
                | US      | /classic-ring-chandelier-sl5812/  |
            And user select the following product variants:
                | country | variant | value                |
                | US      | Finish  | Bronze               |
                | US      | Shade   | 3" x 6.5" X 5" Linen |
            And user click on the add to cart button
            And user click on the begin checkout button from the mini cart
            And user select the following shipping method
                | country | shippingMethod     |
                | US      | Priority Overnight |
            And user click on the continue to payment button
        Then user should see 'FedEx - Priority Overnight' shipping method in order summary section

    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @retail @us @uk @eu @testcase_245
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
            And user click on the begin checkout button from the shopping cart page
        Then the order summary section should be displayed on the shipping page
            And the following item details should be displayed on the order summary section
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |
    
    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @wholesale @us @testcase_246 
    Scenario: The wholesale user should be able to see the order summary section and its details

        Given user is on the login page
        When 'Wholesale' user do login into the website
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
            And user click on the begin checkout button from the shopping cart page
        Then the order summary section should be displayed on the shipping page
            And the following item details should be displayed on the order summary section
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
        
    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @user @us @uk @eu @testcase_247
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
            And user click on the begin checkout button from the shopping cart page
        Then the order summary section should be displayed on the shipping page
            And the following item details should be displayed on the order summary section
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |

    #--------------------------------------------------------------------------------------------------

    @all @shippingPage @trade @us @uk @eu @testcase_248
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
            And user click on the begin checkout button from the shopping cart page
        Then the order summary section should be displayed on the shipping page
            And the following item details should be displayed on the order summary section
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |