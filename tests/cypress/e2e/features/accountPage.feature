Feature: Account Page

    The user should be able to access the account page.

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @testcase_120
    Scenario: The wholesale user should be redirected to the order page after click on the view all link

        Given 'Wholesale' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option     |
                | US      | Account    |
            And user click on the view all link of 'Orders' list
        Then 'Orders' page should get opened

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @testcase_121
    Scenario: The wholesale user should be redirected to the invoice page after click on the view all link

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
            And user click on the view all link of 'Invoices' list
        Then 'Invoices' page should get opened

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @testcase_122
    Scenario: The wholesale user should be redirected to the credit memo page after click on the view all link

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
            And user click on the view all link of 'Credit Memos' list
        Then 'Credit Memos' page should get opened

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @uk @eu @testcase_123
    Scenario: The wholesale user should be able to see a placeholder for the search box 

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
        Then user should be able to see the below placeholder for the search box 
                | placeholder                                            |
                | Search by Order Number, PO Number, Product Name or SKU |

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @uk @eu @testcase_124
    Scenario: The wholesale user should be able to see account header as selected along with other top headers

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
        Then user should be able to see below header as selected
                | country | header     |
                | US      | Account    |
                | UK      | My Account |
                | EU      | My Account |
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                          |
                | US      | ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, ACCOUNT INFORMATION, STORED PAYMENT METHODS, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS |
                | UK      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                   |
                | EU      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                   |

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @uk @eu @testcase_125
    Scenario: The wholesale user should be see search box and account dropdown

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
        Then user should be able see search box and account dropdown

    #--------------------------------------------------------------------------------------------------

    @all @accountPage @wholesale @us @testcase_126
    Scenario: The wholesale user should be able to open credit memo details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
            And user enter 8878060 number in search box
            And user click on the 'View Credit Memo' link of '8878060' number
        Then details page of '8878060' number should get opened

    #--------------------------------------------------------------------------------------------------

    @all @accountPage @wholesale @us @testcase_127
    Scenario: The wholesale user should be able to open invoice details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
            And user enter 8773043 number in search box
            And user click on the 'View Invoice' link of '8773043' number
        Then details page of '8773043' number should get opened
    
    #--------------------------------------------------------------------------------------------------

    @all @accountPage @wholesale @us @testcase_128
    Scenario: The wholesale user should be able to open order details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
            And user enter 12884548 number in search box
            And user click on the 'View Order' link of '12884548' number
        Then details page of '12884548' number should get opened

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @uk @eu @testcase_129
    Scenario: The wholesale user should be able to see account information

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
        Then user should be able to see below account Number
                | country | accountNumber       |
                | US      | GL Account #: 45046 |
            And user should be able to see below email id
                | country | email                                            |
                | US      | test-cypress-wholesale@n8ko5unu.mailosaur.net    |
                | UK      | test-cypress-wholesale-uk@n8ko5unu.mailosaur.net |
                | EU      | test-cypress-wholesale-eu@n8ko5unu.mailosaur.net |
            And user should be able to see below first name and last name
                | country | firstName  | lastName  |
                | US      | Test       | Account   |
                | UK      | Test       | Account   |
                | EU      | Test       | Account   |

    #--------------------------------------------------------------------------------------------------
    
    @all @accountPage @wholesale @us @uk @eu @testcase_197
    Scenario: The wholesale user should be able to see mega menus and its sub-options on the account page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                |
                | /customer/account/ |
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
    
    @all @accountPage @retail @us @uk @eu @testcase_198
    Scenario: The retail user should be able to see mega menus and its sub-options on the account page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user open the following url
                | url                |
                | /customer/account/ |
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
    
    @all @accountPage @trade @us @uk @eu @testcase_199
    Scenario: The trade user should be able to see mega menus and its sub-options on the account page

        Given user is on the login page
        When 'Trade' user do login into the website
            And user open the following url
                | url                |
                | /customer/account/ |
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