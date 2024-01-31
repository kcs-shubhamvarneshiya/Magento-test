Feature: Footer Section

    The user should be able to access the footer section.

    #--------------------------------------------------------------------------------------------------
    
    @all @footer @user @us @uk @eu @testcase_156
    Scenario: The user should be able to see stay in touch section

        When user is on the home page
        Then user should be able to see below details on the stay in touch section
            | label               | value                                                                                |
            | Title               | Stay In Touch                                                                        |
            | Text                | Get all of Visual Comfort's latest news and events delivered straight to your inbox. |
            | Textbox             | Email                                                                                |
            | Textbox Placeholder | email@domain.com                                                                     |
            | Button              | Submit                                                                               |
    
    #--------------------------------------------------------------------------------------------------
    
    @all @footer @user @us @uk @eu @testcase_157
    Scenario: The user should be able to see country options at the footer

        Given user is on the home page
        When user click on the country flag from the footer section
        Then user should be able to see below available countries in the footer section
            | countryName         |
            | United States (US)  |
            | United Kingdom (UK) |
            | European Union (EU) |

    #--------------------------------------------------------------------------------------------------
    
    @all @footer @user @us @uk @eu @testcase_158
    Scenario: The user should be able to see some links on the right side of the footer

        Given user change view port of browser to 1400 and 700 
        When user is on the home page
        Then user should be able to see below link headers on the right side of the footer
                | headers                                                                 |
                | CUSTOMER SERVICE, SHOP YOUR WAY, OUR COMPANY, FOR DESIGN PROS, OUR SITE |
            And user should be able to see some links on the right side of the footer
                | country | links                                                                                                                                                                                                                                                                             |
                | US      | Contact Us, Shipping & Delivery, Returns, FAQs, Warranty, Showroom Locations, Catalogs & Lookbooks, Inspiration, Our Designers, Join Our Team, News & Events, Request A Trade Account, My Account, My Orders, Partners Insight, Terms & Conditions, Privacy Policy, Accessibility |
                | UK      | Contact Us, Shipping & Delivery, Returns, FAQs, Warranty, Showroom Locations, Catalogs & Lookbooks, Our Designers, Join Our Team, News & Events, Request A Trade Account, My Account, My Orders, Partners Insight, Terms & Conditions, Privacy Policy, Accessibility              | 
                | EU      | Contact Us, Shipping & Delivery, Returns, FAQs, Warranty, Showroom Locations, Catalogs & Lookbooks, Our Designers, Join Our Team, News & Events, Request A Trade Account, My Account, My Orders, Partners Insight, Terms & Conditions, Privacy Policy, Accessibility              |  

    #--------------------------------------------------------------------------------------------------
    
    @all @footer @user @us @uk @eu @testcase_160
    Scenario: The user should be able to see the experience visual comfort image, copyright message, and country flag at the bottom of footer

        When user is on the home page
        Then user should be able to see the experience visual comfort image
            And user should be able to see below copyright message
                | message                     |
                | © 2024 Visual Comfort & Co. |
            And user should be able to see country flag at the bottom of footer
                | country | flag    |
                | US      | US Flag |
                | UK      | UK Flag |
                | EU      | EU Flag |
    
    #--------------------------------------------------------------------------------------------------

    @all @footer @user @us @uk @eu @testcase_164
    Scenario: All links of footer should be navigated to the respective pages

        When user is on the home page
        Then the respective pages should open after clicking on all links of footer

    #--------------------------------------------------------------------------------------------------

    @all @footer @retail @us @uk @eu @testcase_165
    Scenario: The respective page should open after clicking on My Account and My Orders links of footer

        When user is on the login page
            And 'Retail' user do login into the website
            And user is on the home page
            And user click on 'My Account' link from footer
        Then the page should open with the below page title
                | country | title      |
                | US      | Account    |
                | UK      | My Account |
                | EU      | My Account |
            And user should be able to see below email id
                | country | email                                         |
                | US      | test-cypress-retail@n8ko5unu.mailosaur.net    |
                | UK      | test-cypress-retail-uk@n8ko5unu.mailosaur.net |
                | EU      | test-cypress-retail-eu@n8ko5unu.mailosaur.net |
            And user should be able to see below first name and last name
                | country | firstName  | lastName  |
                | US      | Test       | Account   |
                | UK      | Test       | Account   |
                | EU      | Test       | Account   |
        When user click on 'My Orders' link from footer
        Then the page should open with the below page title
                | country | title  |
                | US      | Orders |
                | UK      | Orders |
                | EU      | Orders |
    
    #--------------------------------------------------------------------------------------------------

    @all @footer @user @us @uk @eu @testcase_166
    Scenario: The social media icons should be displayed at the footer

        Given user is on the home page
        Then the following social media icons should be displayed at the footer
            | icons     |
            | Instagram |
            | Pinterest |