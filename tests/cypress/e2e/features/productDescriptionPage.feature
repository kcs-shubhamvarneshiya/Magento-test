Feature: Product Description Page

    The user should be able to access the product description page.

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_46
    Scenario: The product name and price should be the same as displayed on the search result page of retail account

        Given user is on the login page
        When 'Retail' user do login into the website
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label         | 
                | Product Name  |
                | Price         |
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label         |
                | Product Name  |
                | Price         |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_47
    Scenario: The product name and price should be the same as displayed on the search result page of wholesale account

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label         | 
                | Product Name  |
                | Price         |
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label         |
                | Product Name  |
                | Price         |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @trade @us @uk @eu @testcase_48
    Scenario: The product name and price should be the same as displayed on the search result page of trade account

        Given user is on the login page
        When 'Trade' user do login into the website
            And user search 'JN5112' 
            And user press enter key
            And user collect the following details of 'JN5112' product from the list
                | label          | 
                | Product Name   |
                | Original Price | 
                | Trade Price    |
            And user click on the 'JN5112' product
        Then the product description page of 'JN5112' should get opened
            And the following product details should be the same as displayed on the list
                | label          |
                | Product Name   |
                | Original Price | 
                | Trade Price    |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_49
    Scenario: The user should not be able to save the product to project without login

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user click on save to project link
        Then user redirect to login page which has below title
                | title |
                | Login |
            And the following validation message should be displayed on login page
                | validationMessage                                        |
                | You must login or register to add items to your project. |      

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_50
    Scenario: The user should be able to add the product to cart   

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
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
                | Finish         |
            And user click on the add to cart button
        Then the following add to cart success message should be displayed
                | successMessage | 
                | You added Talia Large Chandelier to your shopping cart.  |
            And the following item details should be displayed on the mini cart
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_51
    Scenario: The sku code should get changed according to the selected finish from dropdown

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                                         |
                | US      | Finish  | Burnished Silver Leaf and Clear Swirled Glass |
                | UK      | Finish  | Burnished Silver Leaf and Clear Swirled Glass |
                | EU      | Finish  | Burnished Silver Leaf and Clear Swirled Glass |
        Then user should be able to see below sku code
                | country | skuCode          | 
                | US      | JN 5112BSL/CG    |
                | UK      | JN 5112BSL/CG-EU |
                | EU      | JN 5112BSL/CG-EU | 

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_52
    Scenario: The retail user should be able to save the product to project

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user click on save to project link
        Then user should be able to see below success message of save to project or quote
            | message                                                                                   |
            | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @testcase_53 
    Scenario: The user should be able to add the product to cart without bulb 

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
            And user unselect the bulb checkbox
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Finish         |
            And user click on the add to cart button from the product description page
            And user collect the following details of bulb from the add bulb popup
                | label     |
                | Bulb Name |
            And user close the add bulb popup
        Then the following add to cart success message should be displayed
                | successMessage                                          | 
                | You added Talia Large Chandelier to your shopping cart. |
            And the bulb name should not be displayed on the mini cart
            And the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @testcase_54
    Scenario: The user should be able to add the product to cart with a bulb from the add bulb popup 

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
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
        Then user redirect to shopping cart page which has below title
                | title         |
                | Shopping Cart |                                   
            And the following item details should be displayed on the shopping cart page
                | label         |
                | Product Name  |
                | Product Price |
                | Product Qty   |
                | Finish        |
                | Bulb Name     |
                | Bulb Price    |
                | Bulb Qty      |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_55
    Scenario: The retail user should be able to create new project and save product to that new project

        Given user is on the login page
        When 'Retail' user do login into the website
            And user delete 'Cypress Test Project' from 'Projects' page if present
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |
            And user click on the create new project link
            And user enter 'Cypress Test Project' project name
            And user click on the create new project button
        Then the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                           | 
                | Project "Cypress Test Project" was saved.                                                 |
                | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |
            And user delete 'Cypress Test Project' from 'Projects' page if present
    
    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @retail @us @uk @eu @testcase_56
    Scenario: The retail user should be able to change the quantity of product

        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass | 
            And user add 5 quantity of product
            And user click on the add to cart button
        Then the following quantity should be displayed on the mini cart for 'Talia Large Chandelier' product
                | productQuantity |
                | 5               |

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @trade @us @uk @eu @testcase_57
    Scenario: The trade user should be able to save the product to quote

        Given user is on the login page
        When 'Trade' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass | 
            And user click add to quote button
            And user select 'TestCy' from add to quote
        Then user should be able to see below success message of save to project or quote
                | message                                                            |
                | Product Talia Large Chandelier has been added to the quote TestCy. | 

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_58
    Scenario: The sku code and finish option should get changed according to the selected product image

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user click on below product image from the product description page
                | country | image           |
                | US      | JN 5112PW/CG    |
                | UK      | JN 5112PW/CG-EU |
                | EU      | JN 5112PW/CG-EU |  
        Then user should be able to see below finish selected
                | Finish                                |
                | Plaster White and Clear Swirled Glass |
            And user should be able to see below sku code
                | country | skuCode         | 
                | US      | JN 5112PW/CG    |
                | UK      | JN 5112PW/CG-EU |
                | EU      | JN 5112PW/CG-EU |    

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_189
    Scenario: The wholesale user should be able to see mega menus and its sub-options on the product description page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user wait for 5 seconds  
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
    
    @all @productDescriptionPage @trade @us @uk @eu @testcase_190
    Scenario: The trade user should be able to see mega menus and its sub-options on the product description page

        Given user is on the login page
        When 'Trade' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user wait for 5 seconds 
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
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_191
    Scenario: The retail user should be able to see mega menus and its sub-options on the product description page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user wait for 5 seconds 
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
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_192
    Scenario: The user should be able to see mega menus and its sub-options on the product description page

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user wait for 5 seconds 
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
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_221
    Scenario: The user should be able to see the ship by date on the product description page

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |     
        Then the ship by date should be displayed on the product description page

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_222
    Scenario: The retail user should be able to see the ship by date on the product description page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |     
        Then the ship by date should be displayed on the product description page
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_223
    Scenario: The wholesale user should be able to see the ship by date on the product description page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user navigate to product description page of 'JN5112' product
            And user select the following product variants:
                | country | variant | value                        |
                | US      | Finish  | Gild and Clear Swirled Glass |
                | UK      | Finish  | Gild and Clear Swirled Glass |
                | EU      | Finish  | Gild and Clear Swirled Glass |     
        Then the ship by date should be displayed on the product description page

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @user @us @uk @eu @testcase_236
    Scenario: In a guest account, the specifications of product should be changed according to the selected in or cm

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on 'in' specifications
        Then user should be able to see below inch specifications 
                | country | specifications                                                                                                                                                          |
                | US      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
        When user click on 'cm' specifications
        Then user should be able to see below cm specifications
                | country | specifications                                                                                                                                                                       |
                | US      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_237
    Scenario: In a retail account, the specifications of product should be changed according to the selected in or cm

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on 'in' specifications
        Then user should be able to see below inch specifications
                | country | specifications                                                                                                                                                          |
                | US      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
        When user click on 'cm' specifications
        Then user should be able to see below cm specifications
                | country | specifications                                                                                                                                                                       |
                | US      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_238
    Scenario: In a wholesale account, the specifications of product should be changed according to the selected in or cm

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on 'in' specifications
        Then user should be able to see below inch specifications
                | country | specifications                                                                                                                                                          |
                | US      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 24", Width: 33", Canopy: 5" Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
        When user click on 'cm' specifications
        Then user should be able to see below cm specifications
                | country | specifications                                                                                                                                                                       |
                | US      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E12 Candelabra, Wattage: 8 - 40 B11, Assembly Required, Chain Length: 2m, Weight: 37 lbs.                        |
                | UK      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |
                | EU      | Height: 60.96cm, Width: 83.82cm, Canopy: 12.70cm Round, Socket: 8 - E14 Candelabra, Wattage: 8 - 40 B, IP Rating: IP20 Class I, Assembly Required, Chain Length: 2m, Weight: 16.8 kg |       

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @user @us @uk @eu @testcase_239
    Scenario: The user should be able to see olapic section on the product description page

        Given user is on the home page
        When user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |    
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
        Then user should be able to see olapic section on the product description page

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @retail @us @uk @eu @testcase_240
    Scenario: The retail user should be able to see olapic section on the product description page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |    
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
        Then user should be able to see olapic section on the product description page

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_241
    Scenario: The wholesale user should be able to see olapic section on the product description page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |    
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
        Then user should be able to see olapic section on the product description page

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @user @us @uk @eu @testcase_257
    Scenario: The user should be able to add product with different variants
    
        Given user is on the home page
        When user search the following sku code
                | country | skuCode     |
                | US      | 700FJBRL    |
                | UK      | EU-CHC5227  |
                | EU      | EU-CHC5227  |
            And user press enter key
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | 700FJBRL   |
                | UK      | EU-CHC5227 |
                | EU      | EU-CHC5227 |
            And user click on below product image from the product description page
                | country | image          |
                | US      | 700FJBRLFS     |
                | UK      | CHC 5227AI-EU  |
                | EU      | CHC 5227AI-EU  |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
        When user click on the 'Remove' link of product from the mini cart
            And user click on the 'Ok' button from the clear shopping cart pop up
            And user click on below product image from the product description page
                | country | image               |
                | US      | 700FJBRLFS-LEDS930  |
                | UK      | CHC 5227AB-EU       |
                | EU      | CHC 5227AB-EU       |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @retail @us @uk @eu @testcase_258
    Scenario: The retail user should be able to add product with different variants
    
        Given user is on the login page
        When 'Retail' user do login into the website
            And user clear added items from the cart
            And user search the following sku code
                | country | skuCode     |
                | US      | 700FJBRL    |
                | UK      | EU-CHC5227  |
                | EU      | EU-CHC5227  |
            And user press enter key
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | 700FJBRL   |
                | UK      | EU-CHC5227 |
                | EU      | EU-CHC5227 |
            And user click on below product image from the product description page
                | country | image          |
                | US      | 700FJBRLFS     |
                | UK      | CHC 5227AI-EU  |
                | EU      | CHC 5227AI-EU  |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
        When user click on the 'Remove' link of product from the mini cart
            And user click on the 'Ok' button from the clear shopping cart pop up
            And user click on below product image from the product description page
                | country | image               |
                | US      | 700FJBRLFS-LEDS930  |
                | UK      | CHC 5227AB-EU       |
                | EU      | CHC 5227AB-EU       |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @trade @us @uk @eu @testcase_259
    Scenario: The trade user should be able to add product with different variants
    
        Given user is on the login page
        When 'Trade' user do login into the website
            And user clear added items from the cart
            And user search the following sku code
                | country | skuCode     |
                | US      | 700FJBRL    |
                | UK      | EU-CHC5227  |
                | EU      | EU-CHC5227  |
            And user press enter key
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | 700FJBRL   |
                | UK      | EU-CHC5227 |
                | EU      | EU-CHC5227 |
            And user click on below product image from the product description page
                | country | image          |
                | US      | 700FJBRLFS     |
                | UK      | CHC 5227AI-EU  |
                | EU      | CHC 5227AI-EU  |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
        When user click on the 'Remove' link of product from the mini cart
            And user click on the 'Ok' button from the clear shopping cart pop up
            And user click on below product image from the product description page
                | country | image               |
                | US      | 700FJBRLFS-LEDS930  |
                | UK      | CHC 5227AB-EU       |
                | EU      | CHC 5227AB-EU       |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |

    #--------------------------------------------------------------------------------------------------

    @all @productDescriptionPage @wholesale @us @testcase_260
    Scenario: The wholesale user should be able to add product with different variants
    
        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user clear added items from the cart
            And user search the following sku code
                | country | skuCode     |
                | US      | 700FJBRL    |
                | UK      | EU-CHC5227  |
                | EU      | EU-CHC5227  |
            And user press enter key
            And user click on the following sku code from the list
                | country | skuCode    |
                | US      | 700FJBRL   |
                | UK      | EU-CHC5227 |
                | EU      | EU-CHC5227 |
            And user click on below product image from the product description page
                | country | image          |
                | US      | 700FJBRLFS     |
                | UK      | CHC 5227AI-EU  |
                | EU      | CHC 5227AI-EU  |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |
        When user click on the 'Remove' link of product from the mini cart
            And user click on the 'Ok' button from the clear shopping cart pop up
            And user click on below product image from the product description page
                | country | image               |
                | US      | 700FJBRLFS-LEDS930  |
                | UK      | CHC 5227AB-EU       |
                | EU      | CHC 5227AB-EU       |
            And user collect the following details of product from the product description page
                | label         |
                | Product Name  |
                | Product Price |
            And user click on the add to cart button
        Then the following item details should be displayed on the mini cart
                | label         |
                | Product Name  |
                | Product Price |

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @wholesale @us @uk @eu @testcase_261
    Scenario: The wholesale user should be able to verify the more from the series section

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on the view series link from the product description page
        Then user should see 'More from the series' section
            And the product image, name, price, and designer name should be displayed in the more from series section
        When user collect the first product name from the more series section
            And user click on the first product name from the more series section
        Then user navigate to the product description page of first product
                
    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @retail @us @uk @eu @testcase_262
    Scenario: The retail user should be able to verify the more from the series section

        Given user is on the login page
        When 'Retail' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on the view series link from the product description page
        Then user should see 'More from the series' section
            And the product image, name, price, and designer name should be displayed in the more from series section
        When user collect the first product name from the more series section
            And user click on the first product name from the more series section
        Then user navigate to the product description page of first product

    #--------------------------------------------------------------------------------------------------
    
    @all @productDescriptionPage @trade @us @uk @eu @testcase_263
    Scenario: The trade user should be able to verify the more from the series section

        Given user is on the login page
        When 'Trade' user do login into the website
            And user enter the following product description page url:
                | country | productDescriptionPageUrl          |
                | US      | /talia-large-chandelier-jn5112/    |
                | UK      | /talia-large-chandelier-eu-jn5112/ |
                | EU      | /talia-large-chandelier-eu-jn5112/ |
            And user click on the view series link from the product description page
        Then user should see 'More from the series' section
            And the product image, name, price, trade price and designer name should be displayed in the more from series section
        When user collect the first product name from the more series section
            And user click on the first product name from the more series section
        Then user navigate to the product description page of first product          