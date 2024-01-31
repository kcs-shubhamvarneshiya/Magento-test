Feature: Quotes Page

    The user should be able to access the quotes page.

    #--------------------------------------------------------------------------------------------------
    
    @all @quotesPage @trade @us @uk @eu @testcase_173
    Scenario: The trade user should be able to see headers of quotes list

        Given 'Trade' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option    |
                | US      | Quotes    |
                | UK      | Quotes    |
                | EU      | Quotes    |
        Then 'Quotes' page should get loaded
            And user should be able to see below available headers of quotes list
                | headers         |
                | PROJECT NAME    |
                | CREATED BY      |
                | LATEST ACTIVITY |
                | QUOTE TOTAL     |

    #--------------------------------------------------------------------------------------------------
    
    @all @quotesPage @trade @us @uk @eu @testcase_174
    Scenario: The trade user should be able to see quotes header as selected along with other top headers 

        Given 'Trade' user do login into the website with session
        When user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
        Then 'Quotes' page should get loaded
            And user should be able to see 'Quotes' header as selected
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                                |
                | US      | ACCOUNT, ORDERS, QUOTES, PROJECTS, ADDRESS BOOK, ACCOUNT INFORMATION, WALLET, TRADE DOCUMENTS, COMPANY PROFILE, COMPANY USERS, INVENTORY               |
                | UK      | My ACCOUNT, ORDERS, QUOTES, PROJECTS, MY ADDRESS BOOK, My ACCOUNT INFORMATION, MY TRADE DOCUMENTS, MY COMPANY PROFILE, MY COMPANY USERS, INVENTORY     |
                | EU      | My ACCOUNT, ORDERS, QUOTES, PROJECTS, MY ADDRESS BOOK, My ACCOUNT INFORMATION, MY TRADE DOCUMENTS, MY COMPANY PROFILE, MY COMPANY USERS, INVENTORY     |

    #--------------------------------------------------------------------------------------------------
    
    @all @quotesPage @trade @us @uk @eu @testcase_175 @skip
    Scenario: The trade user should be able to add a product in the new quote

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress 01' project from 'Quotes' page
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
            And user click add to quote button
            And user click new quote link 
            And user enter 'Cypress 01' project name in create quote
            And user click on save quote button
        Then user should be able to see below success message of save to project or quote
                | message                                                                |
                | Product Talia Large Chandelier has been added to the quote Cypress 01. |
           
    #--------------------------------------------------------------------------------------------------

    @all @quotesPage @trade @us @testcase_176
    Scenario: The trade user should be able to see 10 as default pagination along with item count

        Given 'Trade' user do login into the website with session
        When user open the following url
            | url                                  |
            | /requisition_list/requisition/index/ |
        Then 'Quotes' page should get loaded
            And user should be able to see 10 as the default pagination on the page
            And the item count should be the same as the added quotes
    
    #--------------------------------------------------------------------------------------------------

    @all @quotesPage @trade @us @uk @eu @testcase_177 @skip
    Scenario: The trade user should be able to see the project name and last activity details on the quote details page

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress 02' project from 'Quotes' page
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
            And user click add to quote button
            And user click new quote link 
            And user enter 'Cypress 02' project name in create quote
            And user click on save quote button
            And user should be able to see below success message of save to project or quote
                | message                                                                |
                | Product Talia Large Chandelier has been added to the quote Cypress 02. |
            And user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
            And user collect the following detail of 'Cypress 02' project
                | Latest Activity |
            And user click on view details of 'Cypress 02' project
        Then user should be able to see below detail on the quote details page
                | projectName  | date            |
                | Cypress 02   | Latest Activity |
    
    #--------------------------------------------------------------------------------------------------

    @all @quotesPage @trade @us @uk @eu @testcase_178 @skip
    Scenario: The trade user should be able to edit the product details

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress 03' project from 'Quotes' page
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
            And user click add to quote button
            And user click new quote link 
            And user enter 'Cypress 03' project name in create quote
            And user click on save quote button
            And user should be able to see below success message of save to project or quote
                | message                                                                |
                | Product Talia Large Chandelier has been added to the quote Cypress 03. |
            And user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
            And user click on view details of 'Cypress 03' project
            And user click on the edit link of product from the quote details page
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label        |
                | Product Name |
                | Product Qty  |
            And user click on the update quote button
        Then the following success message should be displayed on the projects page
                | successMessages                                        | 
                | Talia Large Chandelier has been updated in your quote. |
            And the following item details should be displayed on the quote details page
                | label         |
                | Product Qty   |

    #--------------------------------------------------------------------------------------------------

    @all @quotesPage @trade @us @uk @eu @testcase_179 @skip
    Scenario: The trade user should be able to remove product from the quote details page

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress 04' project from 'Quotes' page
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
            And user click add to quote button
            And user click new quote link 
            And user enter 'Cypress 04' project name in create quote
            And user click on save quote button
            And user should be able to see below success message of save to project or quote
                | message                                                                |
                | Product Talia Large Chandelier has been added to the quote Cypress 04. |
            And user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
            And user click on view details of 'Cypress 04' project
            And user click on the remove link of product from the quote details page
            And user click on the delete button of remove from quote popup
        Then the removed product should not be displayed on the quote details page

    #--------------------------------------------------------------------------------------------------
    
    @all @quotesPage @trade @us @uk @eu @testcase_180 @skip
    Scenario: The trade user should be able to see the quote details on the quotes list

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress 06' project from 'Quotes' page
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
            And user click add to quote button
            And user click new quote link 
            And user enter 'Cypress 06' project name in create quote
            And user click on save quote button
            And user should be able to see below success message of save to project or quote
                | message                                                                |
                | Product Talia Large Chandelier has been added to the quote Cypress 06. |
            And user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
        Then user should be able to see below details of 'Cypress 06' project
                | country  | label               | value                  |
                | US       | Project Name        | Cypress 06             |
                | US       | Created By          | Test Account           |
                | US       | Latest Activity     | Current date           |
                | US       | Quote Total         | It should be displayed |
                | US       | View Quote Details  | It should be displayed |
                | UK       | Project Name        | Cypress 06             |
                | UK       | Created By          | Test Test              |
                | UK       | Latest Activity     | Current date           |
                | UK       | Quote Total         | It should be displayed |
                | UK       | View Quote Details  | It should be displayed |
                | EU       | Project Name        | Cypress 06             |
                | EU       | Created By          | Test Account           |
                | EU       | Latest Activity     | Current date           |
                | EU       | Quote Total         | It should be displayed |
                | EU       | View Quote Details  | It should be displayed |

    #--------------------------------------------------------------------------------------------------

    @all @quotesPage @trade @us @uk @eu @testcase_181
    Scenario: The trade user should be able to create new quote from quotes page

        Given 'Trade' user do login into the website with session
        When user delete 'Cypress Automation 01' project from 'Quotes' page
            And user open the following url
                | url                                  |
                | /requisition_list/requisition/index/ |
            And user click on create new quote button
            And user enter 'Cypress Automation 01' project name in create quote
            And user click on save quote button
        Then user should be able to see below details of 'Cypress Automation 01' project
                | country | label        | value                 |
                | US      | Project Name | Cypress Automation 01 |
                | UK      | Project Name | Cypress Automation 01 |
                | EU      | Project Name | Cypress Automation 01 |
            And user delete 'Cypress Automation 01' project from 'Quotes' page