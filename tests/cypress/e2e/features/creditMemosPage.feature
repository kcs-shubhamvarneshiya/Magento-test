Feature: Credit Memos Page

    The user should be able to access the credit memos page.

    #--------------------------------------------------------------------------------------------------
    
    @all @creditMemosPage @wholesale @us @testcase_112
    Scenario: The wholesale user should be able to see pagination at the bottom of credit memos page

        Given 'Wholesale' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option     |
                | US      | Account    |
            And user click on the 'Credit Memos' header
        Then 'Credit Memos' page should get loaded
            And the pagination should be available at the bottom of page

    #--------------------------------------------------------------------------------------------------
    
    @all @creditMemosPage @wholesale @us @uk @eu @testcase_113
    Scenario: The wholesale user should be see search box and account filter

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
        Then 'Credit Memos' page should get loaded
            And user should be able see search box
        When user click on the filter by
        Then the 'Account' filter should be displayed

    #--------------------------------------------------------------------------------------------------
    
    @all @creditMemosPage @wholesale @us @uk @eu @testcase_114
    Scenario: The wholesale user should be able to see a placeholder for the search box 

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
        Then 'Credit Memos' page should get loaded
            And user should be able to see the below placeholder for the search box 
                | placeholder                                            |
                | Search by Credit Memo Number or  Purchase Order Number |

    #--------------------------------------------------------------------------------------------------
    
    @all @creditMemosPage @wholesale @us @uk @eu @testcase_115
    Scenario: The wholesale user should be able to see credit memos header as selected along with other top headers

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
        Then 'Credit Memos' page should get loaded
            And user should be able to see 'Credit Memos' header as selected 
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                          |
                | US      | ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, ACCOUNT INFORMATION, STORED PAYMENT METHODS, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS |
                | UK      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                   |
                | EU      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                   |
    
    #--------------------------------------------------------------------------------------------------

    @all @creditMemosPage @wholesale @us @testcase_116
    Scenario: The wholesale user should be able to see 20 as default pagination along with other pagination options

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
        Then 'Credit Memos' page should get loaded
            And user should be able to see 20 as default pagination
            And user should be able to see below available pagination options
                | paginationOptions |
                | 10                |
                | 20                |
                | 50                |

    #--------------------------------------------------------------------------------------------------

    @all @creditMemosPage @wholesale @us @testcase_117
    Scenario: The wholesale user should be able to open credit memos details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
            And user enter 8878060 number in search box
            And user click on the view link of '8878060' number
        Then details page of '8878060' number should get opened

    #--------------------------------------------------------------------------------------------------

    @all @creditMemosPage @wholesale @us @testcase_118
    Scenario: The wholesale user should be able to search credit memos number

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
            And user enter 8878060 number in search box
        Then user should be able to see below number in search results
                | number   | 
                | 8878060  |
    
    #--------------------------------------------------------------------------------------------------

    @all @creditMemosPage @wholesale @us @testcase_119
    Scenario: The wholesale user should be able to see headers of credit memos list

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                             |
                | /orderview/creditmemos/history/ |
        Then 'Credit Memos' page should get loaded
            And user should be able to see below available headers of list
                | headers          |
                | CREDIT MEMO      |
                | PURCHASE ORDER   | 
                | CREDIT MEMO DATE |
                | AMOUNT           |
                | DIVISION         |
                | STATUS           |
                | ACTION           |