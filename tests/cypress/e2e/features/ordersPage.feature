Feature: Orders Page

    The user should be able to access the orders page.

    #--------------------------------------------------------------------------------------------------
    
    @all @ordersPage @wholesale @us @uk @eu @testcase_98
    Scenario: The wholesale user should be able to see orders header as selected along with other top headers

        Given 'Wholesale' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option |
                | US      | Orders |
                | UK      | Orders |
                | EU      | Orders |
        Then 'Orders' page should get loaded
            And user should be able to see 'Orders' header as selected 
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                           |
                | US      | ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, ACCOUNT INFORMATION, STORED PAYMENT METHODS, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS  |
                | UK      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |
                | EU      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |

    #--------------------------------------------------------------------------------------------------

    @all @ordersPage @wholesale @us @uk @eu @testcase_99 
    Scenario: The wholesale user should be able to see a placeholder for the search box 

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And user should be able to see the below placeholder for the search box 
                | placeholder                                            |
                | search by order number, po number, product name or sku |

    #--------------------------------------------------------------------------------------------------
    
    @all @ordersPage @wholesale @us @testcase_103
    Scenario: The wholesale user should be able to see headers of orders list

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And user should be able to see below available headers of list
                | headers        |
                | ORDER          |
                | PURCHASE ORDER |
                | DATE           |
                | SHIP TO        |
                | STATUS         |
                | ACTION         |

    #--------------------------------------------------------------------------------------------------
    
    @all @ordersPage @wholesale @us @uk @eu @testcase_104
    Scenario: The wholesale user should be see search box and account filter

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And user should be able see search box
        When user click on the filter by
        Then the 'Account' filter should be displayed

    #--------------------------------------------------------------------------------------------------
    
    @all @ordersPage @wholesale @us @testcase_106 
    Scenario: The wholesale user should be able to see pagination at the bottom of orders page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And the pagination should be available at the bottom of page

    #--------------------------------------------------------------------------------------------------

    @all @ordersPage @wholesale @us @testcase_107 
    Scenario: The wholesale user should be able to see 20 as default pagination along with other pagination options

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And user should be able to see 20 as default pagination
            And user should be able to see below available pagination options
                | paginationOptions |
                | 10                |
                | 20                |
                | 50                | 

    #--------------------------------------------------------------------------------------------------

    @all @ordersPage @wholesale @us @testcase_108
    Scenario: The wholesale user should be able to search order number

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
            And user enter 12884548 number in search box
        Then user should be able to see below number in search results
                | number   | 
                | 12884548 |

    #--------------------------------------------------------------------------------------------------

    @all @ordersPage @wholesale @us @testcase_111
    Scenario: The wholesale user should be able to open order details page

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
            And user enter 12884548 number in search box
            And user click on the view link of '12884548' number
        Then details page of '12884548' number should get opened
    
    #--------------------------------------------------------------------------------------------------

    @all @ordersPage @retail @us @uk @eu @testcase_159
    Scenario: The retail user should be able to see the no orders message if the orders are not available

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                        |
                | /orderview/orders/history/ |
        Then 'Orders' page should get loaded
            And user should be able to see below message if the orders are not available
                | message                    |
                | You have placed no orders. |