Feature: Company Users Page

    The user should be able to access the company users page.

    #--------------------------------------------------------------------------------------------------
    
    @all @companyUsersPage @trade @us @uk @eu @testcase_162
    Scenario: The trade user should be able to see company users header as selected along with other top headers

        Given 'Trade' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option     |
                | US      | Account    |
                | UK      | My Account |
                | EU      | My Account |
            And user click on the 'Company Users' header
        Then 'Company Users' page should get loaded
            And user should be able to see below header as selected 
                | country | header           |
                | US      | Company Users    |
                | UK      | My Company Users |
                | EU      | My Company Users |
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                            |
                | US      | ACCOUNT, ORDERS, QUOTES, PROJECTS, ADDRESS BOOK, ACCOUNT INFORMATION, WALLET, TRADE DOCUMENTS, COMPANY PROFILE, COMPANY USERS, INVENTORY           |
                | UK      | MY ACCOUNT, ORDERS, QUOTES, PROJECTS, MY ADDRESS BOOK, MY ACCOUNT INFORMATION, MY TRADE DOCUMENTS, MY COMPANY PROFILE, MY COMPANY USERS, INVENTORY |
                | EU      | MY ACCOUNT, ORDERS, QUOTES, PROJECTS, MY ADDRESS BOOK, MY ACCOUNT INFORMATION, MY TRADE DOCUMENTS, MY COMPANY PROFILE, MY COMPANY USERS, INVENTORY |

    #--------------------------------------------------------------------------------------------------
    
    @all @companyUsersPage @trade @us @uk @eu @testcase_163
    Scenario: The trade user should be able to add new user

        Given 'Trade' user do login into the website with session
        When user open the following url
                | url             |
                | /company/users/ |
            And user click on the add new user button
            And user enter the following user details:
                | field        | value                                     |
                | Job Title    | Sales                                     |  
                | First Name   | Trade                                     |
                | Last Name    | User Cypress                              |
                | Email        | test-cypress-trade@n8ko5unu.mailosaur.net |
                | Phone Number | 7145155920                                |
            And user click on the save button from the user popup
        Then the following success message should be displayed on the company users page
                | successMessage                         |
                | The customer was successfully created. |
            And the given name and email should be displayed on the company users page 
            And user delete the given user
    
    #--------------------------------------------------------------------------------------------------
    
    @all @companyUsersPage @trade @us @uk @eu @testcase_167
    Scenario: The trade user should be able to edit user details

        Given 'Trade' user do login into the website with session
        When user open the following url
                | url             |
                | /company/users/ |
            And user click on the add new user button
            And user enter the following user details:
                | field        | value                                     |
                | Job Title    | Sales                                     |  
                | First Name   | Barbara                                   |
                | Last Name    | Hepworth                                  |
                | Email        | test-cypress-trade@n8ko5unu.mailosaur.net |
                | Phone Number | 7145155920                                |
            And user click on the save button from the user popup
            And the following success message should be displayed on the company users page
                | successMessage                         |
                | The customer was successfully created. |
            And user click on the edit link of given user from the company users list page
            And user enter the following user details:
                | field        | value             | 
                | First Name   | Test              |
                | Last Name    | Account Cypress   |
            And user click on the save button from the user popup
        Then the following success message should be displayed on the company users page
                | successMessage                         |
                | The customer was successfully updated. |
            And the given name and email should be displayed on the company users page 
            And user delete the given user

    #--------------------------------------------------------------------------------------------------
    
    @all @companyUsersPage @trade @us @uk @eu @testcase_168
    Scenario: The trade user should be able to delete user

        Given 'Trade' user do login into the website with session
        When user open the following url
                | url             |
                | /company/users/ |
            And user click on the add new user button
            And user enter the following user details:
                | field        | value                                     |
                | Job Title    | Sales                                     |  
                | First Name   | Barbara                                   |
                | Last Name    | Hepworth                                  |
                | Email        | test-cypress-trade@n8ko5unu.mailosaur.net |
                | Phone Number | 7145155920                                |
            And user click on the save button from the user popup
            And the following success message should be displayed on the company users page
                | successMessage                         |
                | The customer was successfully created. |
            And user click on the delete link of given user from the company users list page
            And user click on the delete button from the delete user popup
        Then the following success message should be displayed on the company users page
                | successMessage                         |
                | The customer was successfully deleted. |
            And the deleted user should not be displayed on the company users list page