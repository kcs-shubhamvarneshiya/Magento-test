Feature: Home Page

    The user should be able to access the home page.

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_01 
    Scenario: The user should be able to access the home page 

        When user opens the home page url
        Then home page should get opened with below title
            | country | title                                                              |
            | US      | Signature Designer Light Fixtures Experience Visual Comfort & Co.  |
            | UK      | Signature Designer Lighting Experience Visual Comfort & Co.        |
            | EU      | Signature Designer Lighting Experience Visual Comfort & Co.        |

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_73
    Scenario: The user should be able to see the default country and 3 countries should be available in the country dropdown

        Given user is on the home page
        Then user should be able to see the below country as default country
            | country |
            | US      |
            | UK      |
            | EU      |
        When user click on the country flag
        Then user should be able to see below available countries in dropdown
            | countryName         |
            | United States (US)  |
            | United Kingdom (UK) |
            | European Union (EU) |

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_85 
    Scenario: The user should be able to see submenu options from the table mega menu

        Given user is on the home page
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

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_86 
    Scenario: The user should be able to see submenu options from the outdoor mega menu

        Given user is on the home page
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

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @testcase_87 
    Scenario: The user should be able to see submenu options from the fans mega menu

        Given user is on the home page
        When user mouseover on the below mega menu
            | country | megaMenu |
            | US      | Fans     |
        Then user should be able to see below available submenu options of 'Fans' mega menu 
            | country | submenu                                                |
            | US      | INDOOR, INDOOR/OUTDOOR, ACCESSORIES, NEW INTRODUCTIONS |

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @testcase_88 
    Scenario: The user should be able to see submenu options from the architectural mega menu

        Given user is on the home page
        When user mouseover on the below mega menu
            | country | megaMenu      |
            | US      | Architectural |
        Then user should be able to see below available submenu options of 'Architectural' mega menu 
            | country | submenu                                                                                      |
            | US      | RECESSED, CHANNELS-OF-LIGHT, CYLINDERS, MONOPOINT-TASK, MONORAIL, KABLE LITE, BROX, FREEJACK |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_89 
    Scenario: The user should be able to see submenu options from the ceiling mega menu

        Given user is on the home page
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

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_90 
    Scenario: The user should be able to see submenu options from the wall mega menu

        Given user is on the home page
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
    
    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_91 
    Scenario: The user should be able to see submenu options from the floor mega menu

        Given user is on the home page
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
    
    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @uk @eu @testcase_92 
    Scenario: The user should be able to see submenu options from the sale mega menu

        Given user is on the home page
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
    
    @all @homePage @user @us @uk @eu @testcase_93 
    Scenario: The user should be able to see submenu options from the our designers mega menu

        Given user is on the home page
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

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @user @us @testcase_94 
    Scenario: The user should be able to see submenu options from the our collections mega menu

        Given user is on the home page
        When user mouseover on the below mega menu
            | country | megaMenu              |
            | US      | Our Collections       |
        Then user should be able to see below available submenu options of 'Our Collections' mega menu  
            | country | submenu                                                                                                                   |
            | US      | SIGNATURE COLLECTION, MODERN COLLECTION, ARCHITECTURAL COLLECTION, STUDIO COLLECTION, FAN COLLECTION, GENERATION LIGHTING |
       
    #--------------------------------------------------------------------------------------------------

    @all @homePage @user @us @uk @eu @testcase_95 
    Scenario: The user should be able to see all mega menu and search bar 

        When user is on the home page
        Then user should be able to see the search bar is visible
            And user should be able to see below available options of mega menu
                | country | megaMenu                                                                                                     |
                | US      | CEILING, WALL, TABLE, FLOOR, OUTDOOR, FANS, ARCHITECTURAL, OUR COLLECTIONS, OUR DESIGNERS, INSPIRATION, SALE |
                | UK      | CEILING, WALL, TABLE, FLOOR, OUTDOOR, OUR DESIGNERS, OUR COLLECTIONS, SALE                                   |
                | EU      | CEILING, WALL, TABLE, FLOOR, OUTDOOR, OUR DESIGNERS, OUR COLLECTIONS, SALE                                   |

    #--------------------------------------------------------------------------------------------------
    
    @all @homePage @retail @us @uk @eu @testcase_182
    Scenario: The retail user should be able to see mega menus and its sub-options on the home page

        Given user is on the login page
        When 'Retail' user do login into the website
            And user is on the home page
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
    
    @all @homePage @trade @us @uk @eu @testcase_183
    Scenario: The trade user should be able to see mega menus and its sub-options on the home page

        Given user is on the login page
        When 'Trade' user do login into the website
            And user is on the home page
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
    
    @all @homePage @wholesale @us @uk @eu @testcase_184
    Scenario: The wholesale user should be able to see mega menus and its sub-options on the home page

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user is on the home page
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
    
    @all @homePage @user @us @uk @eu @testcase_200 
    Scenario: The respective country website should be opened after clicking on the country from the dropdown

        Given user is on the home page
        When user click on the country flag
            And user click on the below option of country dropdown
                | country | dropdownOption      |
                | US      | European Union (EU) |
                | UK      | United States (US)  |
                | EU      | United Kingdom (UK) |
        Then the website of respective country should be opened
                | country | website               |
                | US      | visualcomfort.com/eu/ |
                | UK      | visualcomfort.com/    |
                | EU      | visualcomfort.com/uk/ |
        When user go back to the previous page 
            And user click on the country flag
            And user click on the below option of country dropdown
                | country | dropdownOption      |
                | US      | United Kingdom (UK) |
                | UK      | European Union (EU) |
                | EU      | United States (US)  |
        Then the website of respective country should be opened
                | country | website               |
                | US      | visualcomfort.com/uk/ |
                | UK      | visualcomfort.com/eu/ |
                | EU      | visualcomfort.com/    |