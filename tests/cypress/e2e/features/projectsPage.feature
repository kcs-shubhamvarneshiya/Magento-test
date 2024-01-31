Feature: Projects Page

    The user should be able to access the projects page.

    #--------------------------------------------------------------------------------------------------
    
    @all @projectsPage @retail @us @uk @eu @testcase_130
    Scenario: The retail user should be able to delete an existing project by clicking the delete project link from the list

        Given 'Retail' user do login into the website with session
        When user click on profile icon
            And user click on below option
                | country | option   |
                | US      | Projects |
                | UK      | Projects |
                | EU      | Projects |
            And user navigate to the 'Projects' page
            And user add new 'Cypress 01' project
            And user click on the 'Delete Project' link of 'Cypress 01' project
            And user click on the 'Yes, Delete Project' button of delete project popup
        Then the following success message should be displayed on the projects page
                | successMessages                     | 
                | Selected projects has been deleted. |
            And the following project should not be displayed on the projects list
                | project    |
                | Cypress 01 |

    #--------------------------------------------------------------------------------------------------
    
    @all @projectsPage @retail @us @uk @eu @testcase_131
    Scenario: The retail user should be able to delete an existing project by clicking the delete selected button

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user add new 'Cypress 02' project
            And user click on the checkbox of 'Cypress 02' project
            And user click on the delete selected button
            And user click on the 'Yes, Delete Project' button of delete project popup
        Then the following success message should be displayed on the projects page
                | successMessages                     | 
                | Selected projects has been deleted. |
            And the following project should not be displayed on the projects list
                | project    |
                | Cypress 02 |

    #--------------------------------------------------------------------------------------------------
    
    @all @projectsPage @retail @us @uk @eu @testcase_133
    Scenario: The retail user should be able to open project details page

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user add new 'Cypress 03' project
            And user click on the 'View Project' link of 'Cypress 03' project
        Then details page of 'Cypress 03' project should get opened

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @testcase_135
    Scenario: The retail user should be able to rename an existing project

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user delete 'Cypress 05' project if present
            And user add new 'Cypress 04' project
            And user click on the 'Cypress 04' project
            And user enter the 'Cypress 05' project name 
            And user click on the 'Save' button of rename project popup
        Then the following success message should be displayed on the projects page
                | successMessages                 | 
                | Project "Cypress 05" was saved. |

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_136
    Scenario: The retail user should be able to see projects header as selected along with other top headers 

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
        Then user should be able to see 'Projects' header as selected
            And user should be able to see below available headers on the projects page
                | country | headers                                                               |
                | US      | ACCOUNT, ORDERS, PROJECTS, ADDRESS BOOK, ACCOUNT INFORMATION, WALLET  |
                | UK      | MY ACCOUNT, ORDERS, PROJECTS, MY ADDRESS BOOK, MY ACCOUNT INFORMATION |
                | EU      | MY ACCOUNT, ORDERS, PROJECTS, MY ADDRESS BOOK, MY ACCOUNT INFORMATION |
    
    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_137
    Scenario: The retail user should be able to see 20 as default pagination along with item count

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
        Then user should be able to see 20 as the default pagination on the page
            And the item count should be the same as the added projects

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_138
    Scenario: The retail user should be able to remove product from the project

        Given 'Retail' user do login into the website with session
        When user delete 'Cypress 06' from 'Projects' page if present
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
            And user click on the create new project link
            And user enter 'Cypress 06' project name
            And user click on the create new project button
            And the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                           | 
                | Project "Cypress 06" was saved.                                                           |
                | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |
            And user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user click on the 'View Project' link of 'Cypress 06' project
            And user click on the 'Remove' link of product from the project page
        Then the following success message should be displayed on the projects page
               | successMessages                                            | 
               | Talia Large Chandelier has been removed from your Project. |
            And the product should not be displayed on the projects page

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_141
    Scenario: The retail user should be able to add products to the cart from the projects page

        Given 'Retail' user do login into the website with session
        When user clear added items from the cart
            And user delete 'Cypress 07' from 'Projects' page if present
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
            And user click on the create new project link
            And user enter 'Cypress 07' project name
            And user click on the create new project button
            And the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                           | 
                | Project "Cypress 07" was saved.                                                           |
                | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |
            And user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user click on the 'View Project' link of 'Cypress 07' project
            And user click on the add to cart button from the projects page
        Then the following success message should be displayed on the projects page
               | successMessages                                         | 
               | You added Talia Large Chandelier to your shopping cart. |
            And the mini cart count should be increased by 1
 
    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_142
    Scenario: The retail user should be able to edit products from the projects page

        Given 'Retail' user do login into the website with session
        When user delete 'Cypress 08' from 'Projects' page if present
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
            And user click on the create new project link
            And user enter 'Cypress 08' project name
            And user click on the create new project button
            And the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                           | 
                | Project "Cypress 08" was saved.                                                           |
                | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |
            And user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user click on the 'View Project' link of 'Cypress 08' project
            And user click on the 'Edit' link of product from the project page
            And user select the following product variants:
                | country | variant | value                                 |
                | US      | Finish  | Plaster White and Clear Swirled Glass |
                | UK      | Finish  | Plaster White and Clear Swirled Glass |
                | EU      | Finish  | Plaster White and Clear Swirled Glass |
            And user add 2 quantity of product
            And user collect the following details of product from the product description page
                | label        |
                | Product Qty  |
                | Finish       |
            And user click on the update project button
        Then the following success message should be displayed on the projects page
                | successMessages                                          | 
                | Talia Large Chandelier has been updated in your Project. |
            And the following item details should be displayed on the projects page
                | label         |
                | Product Qty   |
                | Finish        |

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_169
    Scenario: The retail user should be able to add project from the projects page

        Given 'Retail' user do login into the website with session
        When user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user delete 'Cypress 09' project if present
            And user click on the create new project button from the projects page
            And user enter the 'Cypress 09' new project name
            And user click on the create new project button
        Then the following success message should be displayed on the projects page
                | successMessages                 | 
                | Project "Cypress 09" was saved. | 
            And details page of 'Cypress 09' project should get opened
            And the following message should be displayed on the project details page
                | message                            |
                | You have no items in your project. |

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @retail @us @uk @eu @testcase_227
    Scenario: In a retail account, the product ship by date of projects page should be the same as displayed on the product description page

        Given 'Retail' user do login into the website with session
        When user delete 'Cypress 10' from 'Projects' page if present
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
                | Ship By Date   |
            And user click on the create new project link
            And user enter 'Cypress 10' project name
            And user click on the create new project button
            And the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                           | 
                | Project "Cypress 10" was saved.                                                           |
                | Talia Large Chandelier has been added to your Projects. Click here to view your Projects. |
            And user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user click on the 'View Project' link of 'Cypress 10' project
        Then details page of 'Cypress 10' project should get opened
            And the product ship by date of project page should be the same as displayed on the product description page

    #--------------------------------------------------------------------------------------------------

    @all @projectsPage @wholesale @us @uk @eu @testcase_228
    Scenario: In a wholesale account, the product ship by date of projects page should be the same as displayed on the product description page

        Given 'Wholesale' user do login into the website with session
        When user delete 'Cypress 10' from 'Projects' page if present
            And user enter the following product description page url:
                | country | productDescriptionPageUrl            |
                | US      | /bryant-large-table-lamp-tob3260/    |
                | UK      | /bryant-large-table-lamp-eu-tob3260/ |
                | EU      | /bryant-large-table-lamp-eu-tob3260/ |
            And user select the following product variants:
                | country | variant | value                           |
                | US      | Finish  | Bronze                          |
                | US      | Shade   | 11" x 12" X 12" Linen           |
                | UK      | Finish  | Bronze                          |
                | UK      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen  |
                | EU      | Finish  | Bronze                          |
                | EU      | Shade   | 27.9cm x 30.5cm x 30.5cm Linen  |
            And user collect the following details of product from the product description page
                | label          |
                | Product Name   |
                | Ship By Date   |
            And user click on the create new project link
            And user enter 'Cypress 10' project name
            And user click on the create new project button
            And the following success messages of adding a product to a new project should be displayed
                | successMessages                                                                            | 
                | Project "Cypress 10" was saved.                                                            |
                | Bryant Large Table Lamp has been added to your Projects. Click here to view your Projects. |
            And user open the following url
                | url                   |
                | /wishlist/index/list/ |
            And user navigate to the 'Projects' page
            And user click on the 'View Project' link of 'Cypress 10' project
        Then details page of 'Cypress 10' project should get opened
            And the product ship by date of project page should be the same as displayed on the product description page