Feature: Shopping Cart Page

    The user should be able to access the shopping cart page.

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @user @us @uk @eu @testcase_59
    Scenario: The user should be able to open the shopping cart page

        Given user is on the home page
        When user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
            | url             |
            | /checkout/cart/ | 
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @user @us @uk @eu @testcase_60
    Scenario: The product details should be the same as displayed on the product description page

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
                | Product Price  |
                | Product Qty    |
                | Product SKU    |
                | Finish         |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Product SKU   |
                | Finish        |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @retail @us @uk @eu @testcase_61 
    Scenario: The retail user should be able to clear cart by click on ok button

        Given user is on the login page
        When 'Retail' user do login into the website
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
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the clear cart button from the shopping cart page
            And user click on the 'Ok' button from the clear shopping cart pop up
        Then user should be able to see below message for clear cart
                | message                                  |
                | You have no items in your shopping cart. |

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @uk @eu @testcase_62
    Scenario: The retail user should be able to remove the product from the shopping cart

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the 'Remove' link of product
        Then the product should not be displayed on the shopping cart page

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @user @us @uk @eu @testcase_63
    Scenario: The user should be redirected to the product description page after clicking on the product image from the shopping cart

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
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the product image from the shopping cart page
        Then user should be redirected to the product description page which has the following page title
                | country | title                                              |
                | US      | Talia Large Chandelier - JN5112  Visual Comfort    |
                | UK      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                | EU      | Talia Large Chandelier - EU-JN5112  Visual Comfort |

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @uk @eu @testcase_64
    Scenario: The retail user should be able to update the product details after clicking on the edit link from the shopping cart page 

        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /loras-mini-pendant-p1449/         |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Dark Weathered Iron          |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the 'Edit' link of product
            And user select the following product variants:
                | country | variant | value                                 |
                | US      | Finish  | Chrome                                |
                | UK      | Finish  | Plaster White and Clear Swirled Glass |
                | EU      | Finish  | Plaster White and Clear Swirled Glass |
            And user collect the following details of product from the product description page
                | label        |
                | Product Name |
                | Finish       |
            And user click on the update cart button
        Then user should be able to see the below success message on shopping cart page
                | country | message                                                   |
                | US      | Loras Mini Pendant was updated in your shopping cart.     |
                | UK      | Talia Large Chandelier was updated in your shopping cart. |
                | EU      | Talia Large Chandelier was updated in your shopping cart. |
            And the following item details should be displayed on the shopping cart page
                | label        |
                | Product Name |
                | Finish       |

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @user @us @uk @eu @testcase_65
    Scenario: The user should be redirected to the product description page after clicking on the product name from the shopping cart

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
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the product name from the shopping cart page
        Then user should be redirected to the product description page which has the following page title
                | country | title                                              |
                | US      | Talia Large Chandelier - JN5112  Visual Comfort    |
                | UK      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
                | EU      | Talia Large Chandelier - EU-JN5112  Visual Comfort |
    
    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @testcase_66
    Scenario: The subtotal of product should get updated as per the entered product quantity in a retail account

        Given user is on the login page
        When 'Retail' user do login into the website
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
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user update 3 quantity of product
        Then the subtotal of product should get updated as per the entered 3 product quantity
    
    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @uk @eu @testcase_67
    Scenario: The order subtotal and merchandise total should be displayed correctly in the order summary section of a retail account

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
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the order subtotal displayed in the order summary should be a sum of all products subtotal
            And the merchandise total displayed in the order summary should be a sum of order subtotal, shipping charges and tax

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @uk @eu @testcase_68
    Scenario: After clicking on the move to project link, the product should get removed from the shopping cart page with a success message

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the 'Move to Project' link of product
        Then the product should not be displayed on the shopping cart page
            And user should be able to see the below success message on shopping cart page
                | country | message                                                |
                | US      | Talia Large Chandelier has been moved to your Project. |
                | UK      | Talia Large Chandelier has been moved to your Project. |
                | EU      | Talia Large Chandelier has been moved to your Project. |

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @trade @us @uk @eu @testcase_69
    Scenario: After clicking on the add to quote link, the success message should be displayed and product should not get removed from the shopping cart page 

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the 'Add to Quote' link of product
            And user select 'TestCy' from add to quote popover
            And user click on the add item button from the requisition list popup
        Then user should be able to see the below success message on shopping cart page
                | country | message                                                                               |
                | US      | All the items in your Shopping Cart have been added to the "TestCy" requisition list. |
                | UK      | All the items in your Shopping Cart have been added to the "TestCy" requisition list. |
                | EU      | All the items in your Shopping Cart have been added to the "TestCy" requisition list. |
            And the following item details should be displayed on the shopping cart page
                | label        |
                | Product Name |

    #--------------------------------------------------------------------------------------------------

    @all @shoppingCartPage @retail @us @uk @eu @testcase_70
    Scenario: In a retail account, any products should not get removed from the shopping cart page after clicking on the cancel button of clear cart popup

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
                | label        |
                | Product Name |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the clear cart button from the shopping cart page
            And user click on the 'Cancel' button from the clear shopping cart pop up
        Then the following item details should be displayed on the shopping cart page
                | label        |
                | Product Name |
                | Bulb Name    |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @retail @us @uk @eu @testcase_71
    Scenario: In a retail account, any products should not get removed from the shopping cart page after clicking on the X icon button of clear cart popup 
    
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
                | label        |
                | Product Name |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user navigate to shopping cart page
            And user click on the clear cart button from the shopping cart page
            And user click on the 'X Icon' button from the clear shopping cart pop up
        Then the following item details should be displayed on the shopping cart page
                | label        |
                | Product Name |
                | Bulb Name    |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @retail @us @testcase_170
    Scenario: In a retail account, the product details should be displayed correctly on the shopping cart page
    
        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label          | 
                | Product Name   |
                | Price          | 
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label          |
                | Product Name   |
                | Price          |
        When user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Product SKU    |
                | Finish         |
            And user unselect the bulb checkbox
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Product SKU   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |
            And the subtotal of product should be the multiplication of product price and qty
            And the subtotal of bulb should be the multiplication of bulb price and qty
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @wholesale @us @testcase_171
    Scenario: In a wholesale account, the product details should be displayed correctly on the shopping cart page
    
        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user clear added items from the cart
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label          | 
                | Product Name   |
                | Price          | 
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label          |
                | Product Name   |
                | Price          |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Product SKU    |
                | Finish         |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Product SKU   |
                | Finish        |
            And the subtotal of product should be the multiplication of product price and qty
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @trade @us @uk @eu @testcase_172
    Scenario: In a trade account, the product details should be displayed correctly on the shopping cart page
    
        Given user is on the login page
        When 'Trade' user do login into the website
            And user clear added items from the cart
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label          | 
                | Product Name   |
                | Price          | 
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label          |
                | Product Name   |
                | Price          |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Product SKU    |
                | Finish         |
            And user unselect the bulb checkbox
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label      |
                | Bulb Name  |
                | Bulb Price |
                | Bulb Qty   |
            And user click on the add to cart button from the add bulb popup
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Product SKU   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |
            And the subtotal of product should be the multiplication of product price and qty
            And the subtotal of bulb should be the multiplication of bulb price and qty

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @retail @us @uk @eu @testcase_193
    Scenario: The retail user should be able to see mega menus and its sub-options on the shopping cart page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
        When user wait for 15 seconds 
            And user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Ceiling  |
                | UK      | Ceiling  |
                | EU      | Ceiling  |
        Then user should be able to see below available submenu options of 'Ceiling' mega menu 
                | country | submenu                                                                                             |
                | US      | CEILING LIGHTS, CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS |
                | UK      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
                | EU      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Wall     |
                | UK      | Wall     |
                | EU      | Wall     |
        Then user should be able to see below available submenu options of 'Wall' mega menu 
                | country | submenu                                                   |
                | US      | DECORATIVE, BATH, TASK, PICTURE LIGHTS, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
                | EU      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Table    |
                | UK      | Table    |
                | EU      | Table    |
        Then user should be able to see below available submenu options of 'Table' mega menu 
                | country | submenu                                                      |
                | US      | DECORATIVE, TASK, CORDLESS & RECHARGEABLE, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Floor    |
                | UK      | Floor    |
                | EU      | Floor    |
        Then user should be able to see below available submenu options of 'Floor' mega menu 
                | country | submenu                             |
                | US      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Outdoor  |
                | UK      | Outdoor  |
                | EU      | Outdoor  |
        Then user should be able to see below available submenu options of 'Outdoor' mega menu
                | country | submenu                                                                                          |
                | US      | WALL, CEILING, TABLE & FLOOR, POST, BOLLARD & PATH, STEP LIGHTS, GAS LANTERNS, NEW INTRODUCTIONS |
                | UK      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
                | EU      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Fans     |
        Then user should be able to see below available submenu options of 'Fans' mega menu 
                | country | submenu                                                |
                | US      | INDOOR, INDOOR/OUTDOOR, ACCESSORIES, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Architectural |
        Then user should be able to see below available submenu options of 'Architectural' mega menu 
                | country | submenu                                                                                      |
                | US      | RECESSED, CHANNELS-OF-LIGHT, CYLINDERS, MONOPOINT-TASK, MONORAIL, KABLE LITE, BROX, FREEJACK |
        When user mouseover on the below mega menu
                | country | megaMenu              |
                | US      | Our Collections       |
        Then user should be able to see below available submenu options of 'Our Collections' mega menu  
                | country | submenu                                                                                                                   |
                | US      | SIGNATURE COLLECTION, MODERN COLLECTION, ARCHITECTURAL COLLECTION, STUDIO COLLECTION, FAN COLLECTION, GENERATION LIGHTING |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Our Designers |
                | UK      | Our Designers |
                | EU      | Our Designers |
        Then user should be able to see below available submenu options of 'Our Designers' mega menu  
                | country | submenu |
                | US      | AERIN, ALEXA-HAMPTON, AMBER-LEWIS, AVROKO, BARBARA-BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAMPALIMAUD, CHAPMAN & MYERS, CHRISTIANE LEMIEUX, CHRISTOPHER SPITZMILLER, CLODAGH, DREW & JONATHAN, ELLEN DEGENERES, ERIC COHLER, FISHER WEISMAN, HABLE, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JOHN ROSSELLI, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, LAUREN ROTTET, MARIE FLANIGAN, MICHAEL S SMITH, MICK DE GIULIO, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, RAY BOOTH, RUDOLPH COLBY, SEAN LAVIN, SUZANNE KASLER, THOMAS O'BRIEN, THOM FILICIA, VISUAL COMFORT, WINDSOR SMITH |
                | UK      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
                | EU      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Sale     |
                | UK      | Sale     |
                | EU      | Sale     |
        Then user should be able to see below available submenu options of sale mega menu 
                | country | submenu                                                                                         |
                | US      | LAST CALL, OPEN BOX, CEILING, WALL, TABLE, FLOOR, OUTDOOR, CEILING, WALL, TABLE, FLOOR, OUTDOOR |
                | UK      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |
                | EU      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @trade @us @uk @eu @testcase_194
    Scenario: The trade user should be able to see mega menus and its sub-options on the shopping cart page

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
        When user wait for 15 seconds 
            And user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Ceiling  |
                | UK      | Ceiling  |
                | EU      | Ceiling  |
        Then user should be able to see below available submenu options of 'Ceiling' mega menu 
                | country | submenu                                                                                             |
                | US      | CEILING LIGHTS, CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS |
                | UK      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
                | EU      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Wall     |
                | UK      | Wall     |
                | EU      | Wall     |
        Then user should be able to see below available submenu options of 'Wall' mega menu 
                | country | submenu                                                   |
                | US      | DECORATIVE, BATH, TASK, PICTURE LIGHTS, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
                | EU      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Table    |
                | UK      | Table    |
                | EU      | Table    |
        Then user should be able to see below available submenu options of 'Table' mega menu 
                | country | submenu                                                      |
                | US      | DECORATIVE, TASK, CORDLESS & RECHARGEABLE, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Floor    |
                | UK      | Floor    |
                | EU      | Floor    |
        Then user should be able to see below available submenu options of 'Floor' mega menu 
                | country | submenu                             |
                | US      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Outdoor  |
                | UK      | Outdoor  |
                | EU      | Outdoor  |
        Then user should be able to see below available submenu options of 'Outdoor' mega menu
                | country | submenu                                                                                          |
                | US      | WALL, CEILING, TABLE & FLOOR, POST, BOLLARD & PATH, STEP LIGHTS, GAS LANTERNS, NEW INTRODUCTIONS |
                | UK      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
                | EU      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Fans     |
        Then user should be able to see below available submenu options of 'Fans' mega menu 
                | country | submenu                                                |
                | US      | INDOOR, INDOOR/OUTDOOR, ACCESSORIES, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Architectural |
        Then user should be able to see below available submenu options of 'Architectural' mega menu 
                | country | submenu                                                                                      |
                | US      | RECESSED, CHANNELS-OF-LIGHT, CYLINDERS, MONOPOINT-TASK, MONORAIL, KABLE LITE, BROX, FREEJACK |
        When user mouseover on the below mega menu
                | country | megaMenu              |
                | US      | Our Collections       |
        Then user should be able to see below available submenu options of 'Our Collections' mega menu  
                | country | submenu                                                                                                                   |
                | US      | SIGNATURE COLLECTION, MODERN COLLECTION, ARCHITECTURAL COLLECTION, STUDIO COLLECTION, FAN COLLECTION, GENERATION LIGHTING |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Our Designers |
                | UK      | Our Designers |
                | EU      | Our Designers |
        Then user should be able to see below available submenu options of 'Our Designers' mega menu  
                | country | submenu |
                | US      | AERIN, ALEXA-HAMPTON, AMBER-LEWIS, AVROKO, BARBARA-BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAMPALIMAUD, CHAPMAN & MYERS, CHRISTIANE LEMIEUX, CHRISTOPHER SPITZMILLER, CLODAGH, DREW & JONATHAN, ELLEN DEGENERES, ERIC COHLER, FISHER WEISMAN, HABLE, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JOHN ROSSELLI, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, LAUREN ROTTET, MARIE FLANIGAN, MICHAEL S SMITH, MICK DE GIULIO, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, RAY BOOTH, RUDOLPH COLBY, SEAN LAVIN, SUZANNE KASLER, THOMAS O'BRIEN, THOM FILICIA, VISUAL COMFORT, WINDSOR SMITH |
                | UK      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
                | EU      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Sale     |
                | UK      | Sale     |
                | EU      | Sale     |
        Then user should be able to see below available submenu options of sale mega menu 
                | country | submenu                                                                                         |
                | US      | LAST CALL, OPEN BOX, CEILING, WALL, TABLE, FLOOR, OUTDOOR, CEILING, WALL, TABLE, FLOOR, OUTDOOR |
                | UK      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |
                | EU      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @wholesale @us @uk @eu @testcase_195
    Scenario: The wholesale user should be able to see mega menus and its sub-options on the shopping cart page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
        When user wait for 15 seconds 
            And user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Ceiling  |
                | UK      | Ceiling  |
                | EU      | Ceiling  |
        Then user should be able to see below available submenu options of 'Ceiling' mega menu 
                | country | submenu                                                                                             |
                | US      | CEILING LIGHTS, CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS |
                | UK      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
                | EU      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Wall     |
                | UK      | Wall     |
                | EU      | Wall     |
        Then user should be able to see below available submenu options of 'Wall' mega menu 
                | country | submenu                                                   |
                | US      | DECORATIVE, BATH, TASK, PICTURE LIGHTS, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
                | EU      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Table    |
                | UK      | Table    |
                | EU      | Table    |
        Then user should be able to see below available submenu options of 'Table' mega menu 
                | country | submenu                                                      |
                | US      | DECORATIVE, TASK, CORDLESS & RECHARGEABLE, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Floor    |
                | UK      | Floor    |
                | EU      | Floor    |
        Then user should be able to see below available submenu options of 'Floor' mega menu 
                | country | submenu                             |
                | US      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Outdoor  |
                | UK      | Outdoor  |
                | EU      | Outdoor  |
        Then user should be able to see below available submenu options of 'Outdoor' mega menu
                | country | submenu                                                                                          |
                | US      | WALL, CEILING, TABLE & FLOOR, POST, BOLLARD & PATH, STEP LIGHTS, GAS LANTERNS, NEW INTRODUCTIONS |
                | UK      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
                | EU      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Fans     |
        Then user should be able to see below available submenu options of 'Fans' mega menu 
                | country | submenu                                                |
                | US      | INDOOR, INDOOR/OUTDOOR, ACCESSORIES, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Architectural |
        Then user should be able to see below available submenu options of 'Architectural' mega menu 
                | country | submenu                                                                                      |
                | US      | RECESSED, CHANNELS-OF-LIGHT, CYLINDERS, MONOPOINT-TASK, MONORAIL, KABLE LITE, BROX, FREEJACK |
        When user mouseover on the below mega menu
                | country | megaMenu              |
                | US      | Our Collections       |
        Then user should be able to see below available submenu options of 'Our Collections' mega menu  
                | country | submenu                                                                                                                   |
                | US      | SIGNATURE COLLECTION, MODERN COLLECTION, ARCHITECTURAL COLLECTION, STUDIO COLLECTION, FAN COLLECTION, GENERATION LIGHTING |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Our Designers |
                | UK      | Our Designers |
                | EU      | Our Designers |
        Then user should be able to see below available submenu options of 'Our Designers' mega menu  
                | country | submenu |
                | US      | AERIN, ALEXA-HAMPTON, AMBER-LEWIS, AVROKO, BARBARA-BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAMPALIMAUD, CHAPMAN & MYERS, CHRISTIANE LEMIEUX, CHRISTOPHER SPITZMILLER, CLODAGH, DREW & JONATHAN, ELLEN DEGENERES, ERIC COHLER, FISHER WEISMAN, HABLE, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JOHN ROSSELLI, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, LAUREN ROTTET, MARIE FLANIGAN, MICHAEL S SMITH, MICK DE GIULIO, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, RAY BOOTH, RUDOLPH COLBY, SEAN LAVIN, SUZANNE KASLER, THOMAS O'BRIEN, THOM FILICIA, VISUAL COMFORT, WINDSOR SMITH |
                | UK      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
                | EU      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Sale     |
                | UK      | Sale     |
                | EU      | Sale     |
        Then user should be able to see below available submenu options of sale mega menu 
                | country | submenu                                                                                         |
                | US      | LAST CALL, OPEN BOX, CEILING, WALL, TABLE, FLOOR, OUTDOOR, CEILING, WALL, TABLE, FLOOR, OUTDOOR |
                | UK      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |
                | EU      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @user @us @uk @eu @testcase_196
    Scenario: The user should be able to see mega menus and its sub-options on the shopping cart page

        Given user is on the home page
        When user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
        When user wait for 15 seconds 
            And user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Ceiling  |
                | UK      | Ceiling  |
                | EU      | Ceiling  |
        Then user should be able to see below available submenu options of 'Ceiling' mega menu 
                | country | submenu                                                                                             |
                | US      | CEILING LIGHTS, CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS |
                | UK      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
                | EU      | CHANDELIER, FLUSH-MOUNT, PENDANT, LANTERN, HANGING-SHADE, LINEAR, NEW INTRODUCTIONS                 |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Wall     |
                | UK      | Wall     |
                | EU      | Wall     |
        Then user should be able to see below available submenu options of 'Wall' mega menu 
                | country | submenu                                                   |
                | US      | DECORATIVE, BATH, TASK, PICTURE LIGHTS, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
                | EU      | DECORATIVE, BATH, TASK, PICTURE, NEW INTRODUCTIONS        |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Table    |
                | UK      | Table    |
                | EU      | Table    |
        Then user should be able to see below available submenu options of 'Table' mega menu 
                | country | submenu                                                      |
                | US      | DECORATIVE, TASK, CORDLESS & RECHARGEABLE, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS                          |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Floor    |
                | UK      | Floor    |
                | EU      | Floor    |
        Then user should be able to see below available submenu options of 'Floor' mega menu 
                | country | submenu                             |
                | US      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | UK      | DECORATIVE, TASK, NEW INTRODUCTIONS |
                | EU      | DECORATIVE, TASK, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Outdoor  |
                | UK      | Outdoor  |
                | EU      | Outdoor  |
        Then user should be able to see below available submenu options of 'Outdoor' mega menu
                | country | submenu                                                                                          |
                | US      | WALL, CEILING, TABLE & FLOOR, POST, BOLLARD & PATH, STEP LIGHTS, GAS LANTERNS, NEW INTRODUCTIONS |
                | UK      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
                | EU      | WALL, CEILING, POST, NEW INTRODUCTIONS                                                           |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Fans     |
        Then user should be able to see below available submenu options of 'Fans' mega menu 
                | country | submenu                                                |
                | US      | INDOOR, INDOOR/OUTDOOR, ACCESSORIES, NEW INTRODUCTIONS |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Architectural |
        Then user should be able to see below available submenu options of 'Architectural' mega menu 
                | country | submenu                                                                                      |
                | US      | RECESSED, CHANNELS-OF-LIGHT, CYLINDERS, MONOPOINT-TASK, MONORAIL, KABLE LITE, BROX, FREEJACK |
        When user mouseover on the below mega menu
                | country | megaMenu              |
                | US      | Our Collections       |
        Then user should be able to see below available submenu options of 'Our Collections' mega menu  
                | country | submenu                                                                                                                   |
                | US      | SIGNATURE COLLECTION, MODERN COLLECTION, ARCHITECTURAL COLLECTION, STUDIO COLLECTION, FAN COLLECTION, GENERATION LIGHTING |
        When user mouseover on the below mega menu
                | country | megaMenu      |
                | US      | Our Designers |
                | UK      | Our Designers |
                | EU      | Our Designers |
        Then user should be able to see below available submenu options of 'Our Designers' mega menu  
                | country | submenu |
                | US      | AERIN, ALEXA-HAMPTON, AMBER-LEWIS, AVROKO, BARBARA-BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAMPALIMAUD, CHAPMAN & MYERS, CHRISTIANE LEMIEUX, CHRISTOPHER SPITZMILLER, CLODAGH, DREW & JONATHAN, ELLEN DEGENERES, ERIC COHLER, FISHER WEISMAN, HABLE, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JOHN ROSSELLI, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, LAUREN ROTTET, MARIE FLANIGAN, MICHAEL S SMITH, MICK DE GIULIO, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, RAY BOOTH, RUDOLPH COLBY, SEAN LAVIN, SUZANNE KASLER, THOMAS O'BRIEN, THOM FILICIA, VISUAL COMFORT, WINDSOR SMITH |
                | UK      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
                | EU      | AERIN, ALEXA HAMPTON, AMBER LEWIS, BARBARA BARRY, BARRY GORALNICK, CARRIER AND COMPANY, CHAPMAN & MYERS, CHRISTOPHER SPITZMILLER, ELLEN DEGENERES, ERIC COHLER, IAN K. FOWLER, J. RANDALL POWERS, JOE NYE, JULIE NEILL, KATE SPADE NEW YORK, KELLY WEARSTLER, MARIE FLANIGAN, LAUREN ROTTET, NIERMANN WEEKS, PALOMA CONTRERAS, PETER BRISTOL, RALPH LAUREN, LAUREN RALPH LAUREN, SEAN LAVIN, VISUAL COMFORT, SUZANNE KASLER, THOMAS O'BRIEN                                                                                                                                                                                                   |
        When user mouseover on the below mega menu
                | country | megaMenu |
                | US      | Sale     |
                | UK      | Sale     |
                | EU      | Sale     |
        Then user should be able to see below available submenu options of sale mega menu 
                | country | submenu                                                                                         |
                | US      | LAST CALL, OPEN BOX, CEILING, WALL, TABLE, FLOOR, OUTDOOR, CEILING, WALL, TABLE, FLOOR, OUTDOOR |
                | UK      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |
                | EU      | CEILING, WALL, TABLE, FLOOR, OUTDOOR                                                            |

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @user @us @uk @eu @testcase_224
    Scenario: In a guest account, the product ship by date of cart page should be the same as displayed on the product description page

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
                | Ship By Date   |
            And user click on the add to cart button   
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
            And the product ship by date of cart page should be the same as displayed on the product description page

    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @retail @us @uk @eu @testcase_225
    Scenario: In a retail account, the product ship by date of cart page should be the same as displayed on the product description page

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
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
            And user click on the add to cart button   
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
            And the product ship by date of cart page should be the same as displayed on the product description page
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @wholesale @us @testcase_226
    Scenario: In a wholesale account, the product ship by date of cart page should be the same as displayed on the product description page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl       |
                | US      | /talia-large-chandelier-jn5112/ |
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |             
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
            And user click on the add to cart button   
            And user click on the mini cart icon
        Then the shopping cart page should get opened which should contain the following url
                | url             |
                | /checkout/cart/ | 
            And the product ship by date of cart page should be the same as displayed on the product description page
    
    #--------------------------------------------------------------------------------------------------
    
    @all @shoppingCartPage @user @us @testcase_233
    Scenario: In a guest account, the generation lighting product details should be displayed correctly on the shopping cart page
    
        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Collection' filter
            And user select 'Generation Lighting' option from given filter
        Then the 'Collection : Generation Lighting' option should be displayed as selected filter
            And the following product details should be displayed on the following product card
                | country | label          | value                   |
                | US      | Product Card   | 7728002                 |
                | US      | Designer Name  | Generation Lighting     |
                | US      | Price          | It should be displayed  |
        When user collect the following details of '7728002' product from the list
                | label          | 
                | Product Name   |
                | Designer Name  |
                | Price          | 
            And user click on the '7728002' product
        Then the product description page of '7728002' should get opened
            And the following product details should be the same as displayed on the list
                | label          |
                | Product Name   |
                | Designer Name  |
                | Price          | 
        When user select the following product variants:
                | country | variant   | value                |
                | US      | Finish    | Brushed Nickel       |
                | US      | Lamp Type | Bulb(s) Not Included |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product SKU    |
            And user click on the add to cart button
            And user click on the mini cart icon
            And user navigate to shopping cart page
        Then the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product SKU   |