Feature: Mini Cart

    The user should be able to access the mini cart.

    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_74 
    Scenario: After clicking on the view cart button, the user should be navigated to the shopping cart page

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
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the view cart button from the mini cart
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_75
    Scenario: The product details of the mini cart should be the same as displayed on the product description page

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
            And user mouse hover on the mini cart icon
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_76
    Scenario: The user should be redirected to the product description page after clicking on the product name

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the product name from the mini cart
        Then user should be redirected to the product description page which has the following page title
                | country | title                                              |
                | US      | Talia Large Chandelier - JN5112  Visual Comfort    |
                | UK      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                | EU      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_77
    Scenario: In the case of empty cart, the user should be able to see no items in your shopping cart message

        Given user is on the home page
        When user clear added items from the cart
            And user mouse hover on the mini cart icon
        Then user should be able to see below message on the mini cart
            | message                                  |
            | You have no items in your shopping cart. |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_78
    Scenario: The user should be redirected to the product description page after clicking on the product image

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the product image from the mini cart
        Then user should be redirected to the product description page which has the following page title
                | country | title                                              |
                | US      | Talia Large Chandelier - JN5112  Visual Comfort    |
                | UK      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                | EU      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                
    #--------------------------------------------------------------------------------------------------
    
    @all @miniCart @user @us @uk @eu @testcase_79
    Scenario: After clicking on the begin checkout button, the user should be navigated to the shipping page

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
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the begin checkout button from the mini cart
        Then the shipping page should get opened which should contain the following url
                | url                 |
                | /checkout/#shipping |

    #--------------------------------------------------------------------------------------------------

    @all @miniCart @user @us @testcase_80
    Scenario: The subtotal displayed on the mini cart should be the sum of all product price * qty

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user unselect the bulb checkbox
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user mouse hover on the mini cart icon
        Then the subtotal should be the sum of all product price * qty
    
    #--------------------------------------------------------------------------------------------------

    @all @miniCart @user @us @uk @eu @testcase_81
    Scenario: The user should be able to remove a product from the mini cart by clicking on the ok button of clear shopping cart popup

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the 'Remove' link of product from the mini cart
            And user click on the 'Ok' button from the clear shopping cart pop up
            And user mouse hover on the mini cart icon
        Then the removed product should not be displayed on the mini cart
    
    #--------------------------------------------------------------------------------------------------

    @all @miniCart @user @us @uk @eu @testcase_82
    Scenario: After clicking on the edit link of product, the user should be redirected to the PDP which has update cart button with the same quantity and variant option

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
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the 'Edit' link of product from the mini cart
        Then user should be redirected to the product description page which has the following page title
                | country | title                                              |
                | US      | Talia Large Chandelier - JN5112  Visual Comfort    |
                | UK      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                | EU      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
            And the update cart button should be displayed on the product description page
            And the following details should be the same as entered before on the product description page
                | label          |
                | Product Qty    |
                | Finish         |
    
    #--------------------------------------------------------------------------------------------------

    @all @miniCart @user @us @uk @eu @testcase_83
    Scenario: After clicking on the cancel button of clear shopping cart popup, the product should not get removed from the mini cart
    
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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user click on the 'Remove' link of product from the mini cart
            And user click on the 'Cancel' button from the clear shopping cart pop up
            And user mouse hover on the mini cart icon
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
    
    #--------------------------------------------------------------------------------------------------

    @all @miniCart @user @us @testcase_84
    Scenario: After updating the qty from the mini cart, the subtotal and cart item count also should be updated
    
        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl         |
                | US      | /maverick-coastal-52-ceiling-fan/ |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
            And user click on the add to cart button
            And user mouse hover on the mini cart icon
            And user update the '3' product qty from the mini cart
            And user click on the update button from the mini cart
            And user reload the current page
            And user mouse hover on the mini cart icon
        Then the subtotal and cart item count should be updated as per the updated 3 product qty