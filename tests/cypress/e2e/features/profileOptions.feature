Feature: Profile Options

    Retail, Trade, or Wholesale users can navigate to the profile option from profile dropdown.

    #--------------------------------------------------------------------------------------------------
  
    @all @profileOptions @retail @us @uk @eu @testcase_11 
    Scenario: The retail user should be able to navigate in order page from profile drop down

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option |
                | US      | Orders |
                | UK      | Orders |
                | EU      | Orders |
        Then 'Orders' page should get loaded

    #--------------------------------------------------------------------------------------------------
   
    @all @profileOptions @retail @us @uk @eu @testcase_12 
    Scenario: The retail user should be able to navigate in projects page from profile drop down

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option   |
                | US      | Projects |
                | UK      | Projects |
                | EU      | Projects |
        Then 'Projects' page should get loaded

    #--------------------------------------------------------------------------------------------------
   
    @all @profileOptions @retail @us @uk @eu @testcase_13
    Scenario: The retail user should be able to logout from profile drop down

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option |
                | US      | Logout |
                | UK      | Logout |
                | EU      | Logout |
        Then user should be able to logout with following message
                | logoutMessage      |
                | You are signed out | 
            And user redirect to home page which has below title
                | country | title                                                                |
                | US      | Signature Designer Light Fixtures Experience Visual Comfort & Co.    |
                | UK      | Signature Designer Lighting Experience Visual Comfort & Co.          |
                | EU      | Signature Designer Lighting Experience Visual Comfort & Co.          |

    #--------------------------------------------------------------------------------------------------
   
    @all @profileOptions @retail @us @uk @eu @testcase_44 
    Scenario: The retail user should be able to see options in the profile dropdown

        Given user is on the login page
        When 'Retail' user do login into the website
            And user click on profile icon
        Then the user should be able to see below available profile options   
            | country | options                              |
            | US      | Account, Orders, Projects, Logout    |
            | UK      | My Account, Orders, Projects, Logout |
            | EU      | My Account, Orders, Projects, Logout |
          
    #--------------------------------------------------------------------------------------------------

    @all @profileOptions @trade @us @uk @eu @testcase_14 
    Scenario: The trade user should be able to logout from profile drop down

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option |
                | US      | Logout |
                | UK      | Logout |
                | EU      | Logout |
        Then user should be able to logout with following message
                | logoutMessage      |
                | You are signed out |
            And user redirect to home page which has below title
                | country | title                                                                |
                | US      | Signature Designer Light Fixtures Experience Visual Comfort & Co.    |
                | UK      | Signature Designer Lighting Experience Visual Comfort & Co.          |
                | EU      | Signature Designer Lighting Experience Visual Comfort & Co.          |

    #--------------------------------------------------------------------------------------------------

    @all @profileOptions @trade @us @uk @eu @testcase_15 
    Scenario: The trade user should be able to navigate in quotes page from profile drop down

        Given user is on the login page
        When 'Trade' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option |
                | US      | Quotes |
                | UK      | Quotes |
                | EU      | Quotes |
        Then 'Quotes' page should get loaded
    
    #--------------------------------------------------------------------------------------------------

    @all @profileOptions @wholesale @us @uk @eu @testcase_16 
    Scenario: The wholesale user should be able to navigate in orders page from profile drop down

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option |
                | US      | Orders |
                | UK      | Orders |
                | EU      | Orders |
        Then 'Orders' page should get loaded

    #--------------------------------------------------------------------------------------------------

    @all @profileOptions @wholesale @us @uk @eu @testcase_17 
    Scenario: The wholesale user should be able to navigate in projects page from profile drop down

        Given user is on the login page
        When 'Wholesale' user do login into the website
            And user click on profile icon
            And user click on below option
                | country | option   |
                | US      | Projects |
                | UK      | Projects |
                | EU      | Projects |
        Then 'Projects' page should get loaded