Feature: Create New Account

    The user should be able to create a new account with the application.

    #--------------------------------------------------------------------------------------------------
    
    @all @createNewAccount @retail @us @uk @eu @testcase_27
    Scenario: The retail user should be able to create a new account

        Given user is on the home page
        When user click on the login icon
            And user click on the create an account button
            And user enter the following new retail account details:
                | field      | value                                      |
                | First Name | Barbara                                    |
                | Last Name  | Hepworth                                   |
                | Email      | test-cypress-retail@n8ko5unu.mailosaur.net |
                | Password   | sculpture@stIves123                        |
            And user click on the create account button
        Then new retail account should get created with the following success message:
                | successMessage                                       |
                | Thank you for registering with Visual Comfort & Co.. |
            And welcome message should contain the following first and last name:
                | label      | value                    |
                | First Name | Barbara                  |
                | Last Name  | Hepworth                 |
            And user should get logged into the account

    #--------------------------------------------------------------------------------------------------
    
    @all @createNewAccount @wholesale @trade @us @uk @eu @testcase_161
    Scenario: The wholesale or trade user should be able to request an account

        Given user is on the home page
        When user click on the login icon
            And user click on the request an account button
            And user enter the following account details:
                | field                  | value                                     |
                | First Name             | Barbara                                   |
                | Last Name              | Hepworth                                  |
                | Company Name           | Test Company                              |
                | Email                  | test-cypress-trade@n8ko5unu.mailosaur.net |
                | Password               | sculpture@stIves123                       |
                | Type of Business       | Ecommerce                                 |
                | Interested Collections | VC Signature                              |
            And user enter the following address details:
                | country | field          | value                    |
                | US      | Address Line 1 | 2808 18th St S           |
                | US      | Country        | United States            |
                | US      | City           | Homewood                 |
                | US      | State          | Alabama                  |
                | US      | Zip Code       | 35209-2510               |
                | US      | Phone Number   | 2058475330               |
                | UK      | Address Line 1 | 52 Southend Avenue       |
                | UK      | Country        | United Kingdom           |
                | UK      | City           | Newport                  |
                | UK      | State          | Barton                   |
                | UK      | Zip Code       | PO30 1FL                 |
                | UK      | Phone Number   | 07089196791              |
                | EU      | Address Line 1 | 108, De Hoper North      |
                | EU      | Country        | Netherlands              |
                | EU      | City           | Amsterdam                |
                | EU      | State          | Holland                  |
                | EU      | Zip Code       | 1511 HN                  |
                | EU      | Phone Number   | 07089196791              |
            And user upload 'tradeWholesaleAccountCreationFile.jpg' file located in the fixtures folder
            And user click on the submit account request button
        Then new wholesale or trade account should get created with the following success message
                | successMessage                                                    |
                | Thank you! We're reviewing your request and will contact you soon |