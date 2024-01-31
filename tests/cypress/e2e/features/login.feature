Feature: Login

    Retail, Trade, or Wholesale users can do a login from the home page.

    #--------------------------------------------------------------------------------------------------

    @all @login @retail @us @uk @eu @testcase_02
    Scenario: The retail user should be able to do login from the home page
    
        Given user is on the home page
        When user click on the login icon
            And 'Retail' user do login
        Then user should get logged-in 

    #--------------------------------------------------------------------------------------------------

    @all @login @trade @us @uk @eu @testcase_234
    Scenario: The trade user should be able to do login from the home page
    
        Given user is on the home page
        When user click on the login icon
            And 'Trade' user do login
        Then user should get logged-in 
    
    #--------------------------------------------------------------------------------------------------

    @all @login @wholesale @us @uk @eu @testcase_235
    Scenario: The wholesale user should be able to do login from the home page
    
        Given user is on the home page
        When user click on the login icon
            And 'Wholesale' user do login
        Then user should get logged-in 