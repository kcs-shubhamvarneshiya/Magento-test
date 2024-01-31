Feature: Request To Order Page

    The user should be able to access the request to order page.

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_148
    Scenario: The wholesale user should be able to see 10 as default pagination along with item count

        Given 'Wholesale' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option     |
                | US      | Account    |
                | UK      | My Account |
                | EU      | My Account |
            And user click on the 'Request To Order' header
        Then 'Request To Order' page should get loaded
            And user should be able to see 10 as the default pagination on the request to order page
            And the item count should be the same as the added request to order

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_149
    Scenario: The wholesale user should be able to see request to order header as selected along with other top headers

        Given 'Wholesale' user do login into the website with session
        When user open the following url
                | url                        |
                | /orequest/request/history/ |
        Then 'Request To Order' page should get loaded
            And user should be able to see 'Request To Order' header as selected 
            And user should be able to see below available headers on the page
                | country | headers                                                                                                                                           |
                | US      | ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, ACCOUNT INFORMATION, STORED PAYMENT METHODS, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS  |
                | UK      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |
                | EU      | My ACCOUNT, ORDERS, INVOICES, CREDIT MEMOS, PROJECTS, My ACCOUNT INFORMATION, REQUEST TO ORDER, INVENTORY, DOWNLOADS, PAYMENTS                    |

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_150
    Scenario: The wholesale user should be able to see updated subtotal after updating the product quantity

        Given 'Wholesale' user do login into the website with session
        When user enter the following product description page url:
                | country | productDescriptionPageUrl             |
                | US      | /52-maverick-ii-ceiling-fan-3mavr52/  |
                | UK      | /bryant-large-table-lamp-eu-tob3260/  |
                | EU      | /frankfort-floor-lamp-eu-arn1001/     |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
                | UK      | Finish  | Bronze                                      |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen              |
                | EU      | Finish  | Hand-Rubbed Antique Brass                   |
                | EU      | Shade   | 26.7cm x 29.2cm x 63.5cm Linen              |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on request order button from the product description page
            And user click on view your request to order button from pop up
            And user click on the 'Request To Order' header
            And user click on the view link of below request order number
                | country | number |
                | US      | 4730   |
                | UK      | 4775   |
                | EU      | 4910   |
            And user update 3 quantity of product from the request page
        Then the subtotal of product should get updated as per 3 product quantity
            And user remove added product from request to order page with below message
                | message                        |
                | Item was successfully removed  |

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_151
    Scenario: The wholesale user should be able to add product in request to order

        Given 'Wholesale' user do login into the website with session
        When user enter the following product description page url:
                | country | productDescriptionPageUrl             |
                | US      | /52-maverick-ii-ceiling-fan-3mavr52/  |
                | UK      | /bryant-large-table-lamp-eu-tob3260/  |
                | EU      | /frankfort-floor-lamp-eu-arn1001/     |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
                | UK      | Finish  | Bronze                                      |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen              |
                | EU      | Finish  | Hand-Rubbed Antique Brass                   |
                | EU      | Shade   | 26.7cm x 29.2cm x 63.5cm Linen              |
            And user click on request order button from the product description page
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Product Price  |
                | Product Qty    |
                | Product Sku    |
            And user click on view your request to order button from pop up
        Then the following item details should be displayed on the request order page
                | label         |
                | Product Price |
                | Product Qty   |
                | Product Sku   |
            And user remove added product from request to order page with below message
                | message                        |
                | Item was successfully removed  |
    
    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_152
    Scenario: The wholesale user should be able to verify request details on the request to order list page

        Given 'Wholesale' user do login into the website with session
        When user enter the following product description page url:
                | country | productDescriptionPageUrl             |
                | US      | /52-maverick-ii-ceiling-fan-3mavr52/  |
                | UK      | /bryant-large-table-lamp-eu-tob3260/  |
                | EU      | /frankfort-floor-lamp-eu-arn1001/     |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
                | UK      | Finish  | Bronze                                      |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen              |
                | EU      | Finish  | Hand-Rubbed Antique Brass                   |
                | EU      | Shade   | 26.7cm x 29.2cm x 63.5cm Linen              |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on request order button from the product description page
            And user click on view your request to order button from pop up
            And user click on the 'Request To Order' header
        Then user should be able to see below request to order details
                | country | field              | value                                            |
                | US      | REQUEST TO ORDER   | 4730                                             |  
                | US      | NAME               | Test Account                                     |
                | US      | EMAIL ADDRESS      | test-cypress-wholesale@n8ko5unu.mailosaur.net    |
                | US      | CREATED            | 11.06.23                                         |
                | US      | NO OF ITEMS        | 1                                                |
                | US      | STATUS             | Open                                             |
                | US      | LINK               | View                                             |
                | UK      | REQUEST TO ORDER   | 4775                                             |  
                | UK      | NAME               | Test Account                                     |
                | UK      | EMAIL ADDRESS      | test-cypress-wholesale-uk@n8ko5unu.mailosaur.net |
                | UK      | CREATED            | 11.09.23                                         |
                | UK      | NO OF ITEMS        | 1                                                |
                | UK      | STATUS             | Open                                             |
                | UK      | LINK               | View                                             |
                | EU      | REQUEST TO ORDER   | 4910                                             |  
                | EU      | NAME               | Test Account                                     |
                | EU      | EMAIL ADDRESS      | test-cypress-wholesale-eu@n8ko5unu.mailosaur.net |
                | EU      | CREATED            | 11.16.23                                         |
                | EU      | NO OF ITEMS        | 1                                                |
                | EU      | STATUS             | Open                                             |
                | EU      | LINK               | View                                             |
            And user remove added product from request to order page with below message
                | message                        |
                | Item was successfully removed  |

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_153
    Scenario: The number of added items should be the same as the item count displayed on the request to order list page

        Given 'Wholesale' user do login into the website with session
        When user enter the following product description page url:
                | country | productDescriptionPageUrl             |
                | US      | /52-maverick-ii-ceiling-fan-3mavr52/  |
                | UK      | /bryant-large-table-lamp-eu-tob3260/  |
                | EU      | /frankfort-floor-lamp-eu-arn1001/     |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
                | UK      | Finish  | Bronze                                      |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen              |
                | EU      | Finish  | Hand-Rubbed Antique Brass                   |
                | EU      | Shade   | 26.7cm x 29.2cm x 63.5cm Linen              |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
            And user click on request order button from the product description page
            And user click on view your request to order button from pop up
            And user click on the 'Request To Order' header
            And user collect no of items details from request to order list page for below order number
                | country | number  |
                | US      | 4730    |
                | UK      | 4775    |
                | EU      | 4910    |
            And user click on view link of below order number
                | country | number  |
                | US      | 4730    |
                | UK      | 4775    |
                | EU      | 4910    |
        Then the number of added items on the request to order details page should be the same as the item count
            And user remove added product from request to order page with below message
                | message                        |
                | Item was successfully removed  |

    #--------------------------------------------------------------------------------------------------

    @all @requestToOrderPage @wholesale @us @uk @eu @testcase_229
    Scenario: In a wholesale account, the product ship by date of request to order page should be the same as displayed on the product description page

        Given 'Wholesale' user do login into the website with session
        When user enter the following product description page url:
                | country | productDescriptionPageUrl             |
                | US      | /52-maverick-ii-ceiling-fan-3mavr52/  |
                | UK      | /bryant-large-table-lamp-eu-tob3260/  |
                | EU      | /frankfort-floor-lamp-eu-arn1001/     |
            And user select the following product variants:
                | country | variant | value                                       |
                | US      | Finish  | Matte White Housing With Matte White Blades |
                | UK      | Finish  | Bronze                                      |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen              |
                | EU      | Finish  | Hand-Rubbed Antique Brass                   |
                | EU      | Shade   | 26.7cm x 29.2cm x 63.5cm Linen              |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
            And user click on request order button from the product description page
            And user click on view your request to order button from pop up
        Then the product ship by date of request to order page should be the same as displayed on the product description page
            And user remove added product from request to order page with below message
                | message                        |
                | Item was successfully removed  |