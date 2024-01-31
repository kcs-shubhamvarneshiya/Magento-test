Feature: Invoices Page

    The user should be able to access the invoices page.

    #--------------------------------------------------------------------------------------------------
    
    @all @invoicesPage @wholesale @us @uk @eu @testcase_96
    Scenario: The wholesale user should be able to see a placeholder for the search box 

        Given 'Wholesale' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option     |
                | US      | Account    |
                | UK      | My Account |
                | EU      | My Account |
            And user click on the 'Invoices' header
        Then 'Invoices' page should get loaded
            And user should be able to see the below placeholder for the search box 
                | placeholder                                              |
                | Search by Invoice Number, PO Number, Product Name or SKU |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @invoicesPage @wholesale @us @uk @eu @testcase_97
    Scenario: The wholesale user should be able to see invoices header as selected along with other top headers

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
        Then 'Invoices' page should get loaded
            And user should be able to see 'Invoices' header as selected 
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                           |
                | US      | ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, ACCOUNT INFORMATION, STORED PAYMENT METHODS, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS  |
                | UK      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |
                | EU      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @invoicesPage @wholesale @us @testcase_100
    Scenario: The wholesale user should be able to see pagination at the bottom of invoices page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
        Then 'Invoices' page should get loaded
            And the pagination should be available at the bottom of page
    
    #--------------------------------------------------------------------------------------------------

    @all @invoicesPage @wholesale @us @testcase_101
    Scenario: The wholesale user should be able to see 20 as default pagination along with other pagination options

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
        Then 'Invoices' page should get loaded
            And user should be able to see 20 as default pagination
            And user should be able to see below available pagination options
                | paginationOptions |
                | 10                |
                | 20                |
                | 50                |   

    #--------------------------------------------------------------------------------------------------

    @all @invoicesPage @wholesale @us @testcase_102
    Scenario: The wholesale user should be able to see headers of invoices list

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
        Then 'Invoices' page should get loaded
            And user should be able to see below available headers of list
                | headers        |
                | INVOICE        |
                | PURCHASE ORDER |
                | INVOICE DATE   |
                | DUE DATE       |
                | AMOUNT         |
                | PAID TO DATE   |
                | DIVISION       |
                | STATUS         |
                | ACTION         |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @invoicesPage @wholesale @us @uk @eu @testcase_105
    Scenario: The wholesale user should be see search box and account filter

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
        Then 'Invoices' page should get loaded
            And user should be able see search box
        When user click on the filter by
        Then the 'Account' filter should be displayed
    
    #--------------------------------------------------------------------------------------------------

    @all @invoicesPage @wholesale @us @testcase_109
    Scenario: The wholesale user should be able to search invoice number

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
            And user enter 8773043 number in search box
        Then user should be able to see below number in search results
                | number   | 
                | 8773043  |
    
    #--------------------------------------------------------------------------------------------------

    @all @invoicesPage @wholesale @us @testcase_110
    Scenario: The wholesale user should be able to open invoice details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                          |
                | /orderview/invoices/history/ |
            And user enter 8773043 number in search box
            And user click on the view link of '8773043' number
        Then details page of '8773043' number should get opened