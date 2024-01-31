Feature: Product List Page

    The user should be able to access the product list page.

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_24 
    Scenario: The user should be able to navigate the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
        Then product list page of 'Ceiling' mega menu should get opened
            And the below breadcrumb value should be displayed on the product list page
                | value          |
                | Home / Ceiling |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_28
    Scenario: The user should be able to see featured as by default selected option of sort by dropdown

        Given user is on the home page
        When user click on 'Ceiling' mega menu
        Then product list page of 'Ceiling' mega menu should get opened
            And the below option of sort by dropdown should be displayed as by default selected
                | option    |
                | FEATURED  |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_33 
    Scenario: The user should be able to select black option from finish filter on the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter 
                
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_29
    Scenario: The user should be able to see available filters on the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
        Then product list page of 'Ceiling' mega menu should get opened
            And user should be able to see below available filters on product list page
                | filters      |
                | FINISH       |
                | PRICE        |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @retail @us @uk @eu @testcase_32
    Scenario: The retail user should be able to see product name, designer name, sku code, price and view additional finishes text on product card

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
        Then product list page of 'Chandelier' mega menu should get opened
            And the following product details should be displayed on the following product card
                | country | label              | value                    |
                | US      | Product Card       | JN5112                   |
                | US      | Product Name       | Talia Large Chandelier   |  
                | US      | Designer Name      | Julie Neill              |
                | US      | SKU Code           | JN5112                   |
                | US      | Price              | It should be displayed   |
                | US      | View More Link     | View Additional Finishes |
                | UK      | Product Card       | EU-TOB5002               |
                | UK      | Product Name       | Bryant Small Chandelier  |
                | UK      | Designer Name      | Thomas O'Brien           |  
                | UK      | SKU Code           | EU-TOB5002               |
                | UK      | Price              | It should be displayed   |
                | UK      | View More Link     | View Additional Finishes |
                | EU      | Product Card       | EU-TOB5002               |
                | EU      | Product Name       | Bryant Small Chandelier  |  
                | EU      | Designer Name      | Thomas O'Brien           |
                | EU      | SKU Code           | EU-TOB5002               |
                | EU      | Price              | It should be displayed   |
                | EU      | View More Link     | View Additional Finishes |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @trade @us @uk @eu @testcase_37
    Scenario: The trade user should be able to see product name, designer name, sku code, price and view additional finishes text on product card

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
        Then product list page of 'Chandelier' mega menu should get opened
            And the following product details should be displayed on the following product card
                | country | label              | value                    |
                | US      | Product Card       | JN5112                   |
                | US      | Product Name       | Talia Large Chandelier   |  
                | US      | Designer Name      | Julie Neill              |
                | US      | SKU Code           | JN5112                   |
                | US      | Original Price     | It should be displayed   |
                | US      | Price              | It should be displayed   |
                | US      | View More Link     | View Additional Finishes |
                | UK      | Product Card       | EU-TOB5002               |
                | UK      | Product Name       | Bryant Small Chandelier  |
                | UK      | Designer Name      | Thomas O'Brien           |  
                | UK      | SKU Code           | EU-TOB5002               |
                | UK      | Original Price     | It should be displayed   |
                | UK      | Price              | It should be displayed   |
                | UK      | View More Link     | View Additional Finishes |
                | EU      | Product Card       | EU-TOB5002               |
                | EU      | Product Name       | Bryant Small Chandelier  |  
                | EU      | Designer Name      | Thomas O'Brien           |
                | EU      | SKU Code           | EU-TOB5002               |
                | EU      | Original Price     | It should be displayed   |
                | EU      | Price              | It should be displayed   |
                | EU      | View More Link     | View Additional Finishes |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_38
    Scenario: The user should be able to see 48 products on the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
        Then product list page of 'Ceiling' mega menu should get opened
            And 48 products should be displayed on the product list page

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @wholesale @us @uk @eu @testcase_39
    Scenario: The wholesale user should be able to see product name, designer name, sku code, price and view additional finishes text on product card

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
        Then product list page of 'Chandelier' mega menu should get opened
            And the following product details should be displayed on the following product card
                | country | label              | value                    |
                | US      | Product Card       | JN5112                   |
                | US      | Product Name       | Talia Large Chandelier   |  
                | US      | Designer Name      | Julie Neill              |
                | US      | SKU Code           | JN5112                   |
                | US      | Price              | It should be displayed   |
                | US      | View More Link     | View Additional Finishes |
                | UK      | Product Card       | EU-TOB5002               |
                | UK      | Product Name       | Bryant Small Chandelier  |
                | UK      | Designer Name      | Thomas O'Brien           |  
                | UK      | SKU Code           | EU-TOB5002               |
                | UK      | Price              | It should be displayed   |
                | UK      | View More Link     | View Additional Finishes |
                | EU      | Product Card       | EU-TOB5002               |
                | EU      | Product Name       | Bryant Small Chandelier  |  
                | EU      | Designer Name      | Thomas O'Brien           |
                | EU      | SKU Code           | EU-TOB5002               |
                | EU      | Price              | It should be displayed   |
                | EU      | View More Link     | View Additional Finishes |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @eu @testcase_42
    Scenario: The user should be able to see product name, designer name, sku code, price and view additional finishes text on product card

        Given user is on the home page
        When user click on the 'Chandelier' submenu of 'Ceiling' mega menu
        Then product list page of 'Chandelier' mega menu should get opened
            And the following product details should be displayed on the following product card
                | country | label              | value                    |
                | US      | Product Card       | JN5112                   |
                | US      | Product Name       | Talia Large Chandelier   |  
                | US      | Designer Name      | Julie Neill              |
                | US      | SKU Code           | JN5112                   |
                | US      | Price              | It should be displayed   |
                | US      | View More Link     | View Additional Finishes |
                | EU      | Product Card       | EU-TOB5002               |
                | EU      | Product Name       | Bryant Small Chandelier  |  
                | EU      | Designer Name      | Thomas O'Brien           |
                | EU      | SKU Code           | EU-TOB5002               |
                | EU      | Price              | It should be displayed   |
                | EU      | View More Link     | View Additional Finishes |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_43
    Scenario: The user should be able to see pagination at the bottom of product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
        Then product list page of 'Ceiling' mega menu should get opened
            And pagination should be available at the bottom of page

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_45
    Scenario: The user should be able to apply the product price filter by moving the price slider

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Price' filter
            And user move a slider under price filter
            And user click on 'Price' filter
        Then the price range displayed on the applied filter and price filter popup should be the same

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @testcase_72
    Scenario: The user should be able to apply the generation lighting filter and able to see generation lighting text on product card

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Collection' filter
            And user select 'Generation Lighting' option from given filter
        Then the 'Collection : Generation Lighting' option should be displayed as selected filter 
            And the following product details should be displayed on the product card of '7728002'
                | country | label         | value                |
                | US      | Designer Name | Generation Lighting  |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @wholesale @us @uk @eu @testcase_185
    Scenario: The wholesale user should be able to see mega menus and its sub-options on the product list page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on 'Ceiling' mega menu
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
    
    @all @productListPage @trade @us @uk @eu @testcase_186
    Scenario: The trade user should be able to see mega menus and its sub-options on the product list page

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on 'Ceiling' mega menu
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
    
    @all @productListPage @retail @us @uk @eu @testcase_187
    Scenario: The retail user should be able to see mega menus and its sub-options on the product list page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on 'Ceiling' mega menu
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
    
    @all @productListPage @user @us @uk @eu @testcase_188
    Scenario: The user should be able to see mega menus and its sub-options on the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
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
    
    @all @productListPage @wholesale @us @uk @eu @testcase_206
    Scenario: The wholesale user should be able to see mega menus and their sub-options after applying a filter on the product list page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter 
        When user mouseover on the below mega menu
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
    
    @all @productListPage @retail @us @uk @eu @testcase_207
    Scenario: The retail user should be able to see mega menus and their sub-options after applying a filter on the product list page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter 
        When user mouseover on the below mega menu
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
    
    @all @productListPage @user @us @uk @eu @testcase_208
    Scenario: The user should be able to see mega menus and their sub-options after applying a filter on the product list page

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter 
        When user mouseover on the below mega menu
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
    
    @all @productListPage @user @us @uk @eu @testcase_209
    Scenario: The user should be able to apply multiple filters and able to see available filters

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
            And user click on 'Price' filter
            And user move a slider under price filter
        Then the user should be able to see the following selected multiple filters
                | filterName | filterValue                         |
                | Finish     | Black                               |
                | Price      | The price range should be displayed |
            And user should be able to see below available filters on product list page
                | filters |
                | Finish  |
                | Price   |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @retail @us @uk @eu @testcase_210
    Scenario: The retail user should be able to apply multiple filters and able to see available filters

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
            And user click on 'Price' filter
            And user move a slider under price filter
        Then the user should be able to see the following selected multiple filters
                | filterName | filterValue                         |
                | Finish     | Black                               |
                | Price      | The price range should be displayed |
            And user should be able to see below available filters on product list page
                | filters |
                | Finish  |
                | Price   |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @wholesale @us @uk @eu @testcase_211
    Scenario: The wholesale user should be able to apply multiple filters and able to see available filters

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
            And user click on 'Price' filter
            And user move a slider under price filter
        Then the user should be able to see the following selected multiple filters
                | filterName | filterValue                         |
                | Finish     | Black                               |
                | Price      | The price range should be displayed |
            And user should be able to see below available filters on product list page
                | filters |
                | Finish  |
                | Price   |

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @wholesale @us @uk @eu @testcase_218
    Scenario: The wholesale user should be able to see products after applying the filter

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter
            And the products should be displayed on the product list page

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @retail @us @uk @eu @testcase_219
    Scenario: The retail user should be able to see products after applying the filter

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter
            And the products should be displayed on the product list page

    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @uk @eu @testcase_220
    Scenario: The user should be able to see products after applying the filter

        Given user is on the home page
        When user click on 'Ceiling' mega menu
            And user navigate to the product list page of 'Ceiling' mega menu
            And user click on 'Finish' filter
            And user select 'Black' option from given filter
        Then the 'Finish : Black' option should be displayed as selected filter
            And the products should be displayed on the product list page
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @retail @us @uk @eu @testcase_253
    Scenario: In a retail account, the product image, SKU, price and finish should be changed as per the selected product variant

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user find the following sku code from the list
                | country | productSku |
                | US      | S5183      |
                | UK      | EU-TOB5002 |
                | EU      | EU-TOB5002 |
            And user mouse hover on the following variant sku of the following product sku
                | country | productSku | variantSku        |
                | US      | S5183      | S 5183SB-L        |
                | UK      | EU-TOB5002 | TOB 5002HAB-L-EU  |
                | EU      | EU-TOB5002 | TOB 5002PN-L-EU   |
        Then the following details should be displayed on the following product card
                | country | label        | value                                       |
                | US      | Card         | Reese 22" Pendant                           |
                | US      | Image        | s5183sbl_12.png                             |  
                | US      | SKU          | S 5183SB-L                                  |
                | US      | Price        | It should be displayed                      |
                | US      | Variant Link | Soft Brass with Linen Shade                 |
                | UK      | Card         | Bryant Small Chandelier                     |
                | UK      | Image        | tob5002habl_1.png                           |  
                | UK      | SKU          | TOB 5002HAB-L-EU                            |
                | UK      | Price        | It should be displayed                      |
                | UK      | Variant Link | Hand-Rubbed Antique Brass with Linen Shades |
                | EU      | Card         | Bryant Small Chandelier                     |
                | EU      | Image        | tob5002pnl_1.png                            |  
                | EU      | SKU          | TOB 5002PN-L-EU                             |
                | EU      | Price        | It should be displayed                      |
                | EU      | Variant Link | Polished Nickel with Linen Shades           |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @trade @us @uk @eu @testcase_254
    Scenario: In a trade account, the product image, SKU, price and finish should be changed as per the selected product variant

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user find the following sku code from the list
                | country | productSku |
                | US      | S5183      |
                | UK      | EU-TOB5002 |
                | EU      | EU-TOB5002 |
            And user mouse hover on the following variant sku of the following product sku
                | country | productSku | variantSku        |
                | US      | S5183      | S 5183SB-L        |
                | UK      | EU-TOB5002 | TOB 5002HAB-L-EU  |
                | EU      | EU-TOB5002 | TOB 5002PN-L-EU   |
        Then the following details should be displayed on the following product card
                | country | label          | value                                       |
                | US      | Card           | Reese 22" Pendant                           |
                | US      | Image          | s5183sbl_12.png                             |  
                | US      | SKU            | S 5183SB-L                                  |
                | US      | Original Price | It should be displayed                      |
                | US      | Price          | It should be displayed                      |
                | US      | Variant Link   | Soft Brass with Linen Shade                 |
                | UK      | Card           | Bryant Small Chandelier                     |
                | UK      | Image          | tob5002habl_1.png                           |  
                | UK      | SKU            | TOB 5002HAB-L-EU                            |
                | UK      | Original Price | It should be displayed                      |
                | UK      | Price          | It should be displayed                      |
                | UK      | Variant Link   | Hand-Rubbed Antique Brass with Linen Shades |
                | EU      | Card           | Bryant Small Chandelier                     |
                | EU      | Image          | tob5002pnl_1.png                            |  
                | EU      | SKU            | TOB 5002PN-L-EU                             |
                | EU      | Original Price | It should be displayed                      |
                | EU      | Price          | It should be displayed                      |
                | EU      | Variant Link   | Polished Nickel with Linen Shades           |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @wholesale @us @uk @eu @testcase_255
    Scenario: In a wholesale account, the product image, SKU, price and finish should be changed as per the selected product variant

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user find the following sku code from the list
                | country | productSku |
                | US      | S5183      |
                | UK      | EU-TOB5002 |
                | EU      | EU-TOB5002 |
            And user mouse hover on the following variant sku of the following product sku
                | country | productSku | variantSku        |
                | US      | S5183      | S 5183SB-L        |
                | UK      | EU-TOB5002 | TOB 5002HAB-L-EU  |
                | EU      | EU-TOB5002 | TOB 5002PN-L-EU   |
        Then the following details should be displayed on the following product card
                | country | label        | value                                       |
                | US      | Card         | Reese 22" Pendant                           |
                | US      | Image        | s5183sbl_12.png                             |  
                | US      | SKU          | S 5183SB-L                                  |
                | US      | Price        | It should be displayed                      |
                | US      | Variant Link | Soft Brass with Linen Shade                 |
                | UK      | Card         | Bryant Small Chandelier                     |
                | UK      | Image        | tob5002habl_1.png                           |  
                | UK      | SKU          | TOB 5002HAB-L-EU                            |
                | UK      | Price        | It should be displayed                      |
                | UK      | Variant Link | Hand-Rubbed Antique Brass with Linen Shades |
                | EU      | Card         | Bryant Small Chandelier                     |
                | EU      | Image        | tob5002pnl_1.png                            |  
                | EU      | SKU          | TOB 5002PN-L-EU                             |
                | EU      | Price        | It should be displayed                      |
                | EU      | Variant Link | Polished Nickel with Linen Shades           |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @productListPage @user @us @testcase_256
    Scenario: In a guest account, the product image, SKU, price and finish should be changed as per the selected product variant

        Given user is on the home page
        When user click on the 'Chandelier' submenu of 'Ceiling' mega menu
            And user find the following sku code from the list
                | country | productSku |
                | US      | S5183      |
            And user mouse hover on the following variant sku of the following product sku
                | country | productSku | variantSku        |
                | US      | S5183      | S 5183SB-L        |
        Then the following details should be displayed on the following product card
                | country | label        | value                                       |
                | US      | Card         | Reese 22" Pendant                           |
                | US      | Image        | s5183sbl_12.png                             |  
                | US      | SKU          | S 5183SB-L                                  |
                | US      | Price        | It should be displayed                      |
                | US      | Variant Link | Soft Brass with Linen Shade                 |