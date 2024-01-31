Note: These requests were made on 2020-05-23.  Request body and response body from these requests are shown.  Calling
the api with the same parameters may give different results.  This is just to demonstrate the API request/response
format.  If this changes, this document should be updated.

## Header Notes:

For GET api/Session, you must pass an "Authorization" header which is generated with this code snippet:

    $basic = base64_encode($user . ':' . $pass);
    $header = ['Authorization' => "Basic $basic"];

Where $user and $pass are the api username and password respectively.

All other requests will use the header:

    $header = ['Session-ID' => $sessionId];

Where $sessionId is the "SessionID" value from the api/Session request.

### GET api/Session

Response:

    {
      "SessionID": "e0094ea8-3ca3-465f-872a-9cd8e3f51c58",
      "SystemGroup": {
        "Security_Group": "Admin",
        "Security": null,
        "Is_External_Group": false,
        "Layout_Path": "",
        "UserFieldData": null,
        "UserFieldNames": null,
        "Notifications": []
      },
      "APIObjectPermissions": [],
      "APIPropertyPermissions": [],
      "APIVisualPermissions": [],
      "APIUserCustomers": [],
      "APIGroupPermission": {
        "GroupName": "ADMIN",
        "Is_Internal_Admin": true,
        "Is_External_Admin": true,
        "UserFieldData": null,
        "UserFieldNames": null,
        "Notifications": []
      },
      "NeedsPasswordReset": false
    }

### GET api/Session/ActiveUsers

Response:

    {
      "InternalLicensedSeatCount": 154,
      "ActiveInternalUserCount": 30,
      "ExternalLicensedSeatCount": 0,
      "ExternalUserCount": 0,
      "ActiveExternalUserCount": 0
    }

### GET api/Session/Ping

Response:

    {
      "StatusCode": "OK",
      "ErrorCode": 0,
      "ErrorCodeMessage": "No Error",
      "Messages": [
        "Session is active"
      ]
    }

### POST api/Customer

Request:

    {
        "Customer_Name" : "Logan Test",
        "Primary_Addr_Code": "PRIMARY",
        "Primary_Bill_To_Addr_Code" : "PRIMARY",
        "Primary_Ship_To_Addr_Code" : "PRIMARY",
        "Customer_Class" : "RETAIL",
        "Price_Level" : "RETAIL",
        "Payment_Terms" : "PREPAID"
    }

Response:

    {
      "Customer_Num": "001624",
      "Customer_Name": "Logan Test",
      "Customer_Class": "RETAIL",
      "Corporate_Customer_Num": "",
      "Short_Name": "",
      "Statement_Name": "",
      "Primary_Addr_Code": "PRIMARY",
      "Primary_Bill_To_Addr_Code": "PRIMARY",
      "Primary_Ship_To_Addr_Code": "PRIMARY",
      "Statement_To_Addr_Code": "",
      "Sales_Person_ID": "",
      "Sales_Territory": "",
      "Payment_Terms": "PREPAID",
      "Shipping_Method": "",
      "Price_Level": "RETAIL",
      "User_Def_1": "",
      "User_Def_2": "",
      "Tax_Exempt_1": "",
      "Tax_Exempt_2": "",
      "Tax_Registration_Num": "",
      "Comment_1": "",
      "Comment_2": "",
      "IntegrationSource": 0,
      "Inactive": false,
      "On_Hold": false,
      "Note": "",
      "Currency_ID": "Z-US$",
      "Currency_Dec": 2,
      "Last_Aged": "1900-01-01T00:00:00.000Z",
      "Balance": 0.0,
      "Unapplied_Amount": 0.0,
      "Customer_Credit_Limit": 0.0,
      "Last_Pay_Date": "1900-01-01T00:00:00.000Z",
      "Last_Pay_Amt": 0.0,
      "First_Invoice_Date": "1900-01-01T00:00:00.000Z",
      "Last_Invoice_Date": "1900-01-01T00:00:00.000Z",
      "Last_Invoice_Amt": 0.0,
      "Last_Stmt_Date": "1900-01-01T00:00:00.000Z",
      "Last_Stmt_Amt": 0.0,
      "Life_Avg_Days": 0,
      "Year_Avg_Days": 0,
      "Total_Amt_NSF_Checks_YTD": 0.0,
      "Num_NSF_Checks_YTD": 0,
      "Tax_Schedule": "",
      "Ship_Complete": false,
      "Stmt_Email_To": "",
      "Stmt_Email_CC": "",
      "Stmt_Email_BCC": "",
      "Email_To": "",
      "Email_CC": "",
      "Email_BCC": "",
      "Message": "",
      "USERDEF1": "",
      "USERDEF2": "",
      "Trade_Discount": 0.0,
      "Master_Distributor": "",
      "Method_Of_Billing": 0,
      "Send_Email_Statements": 0,
      "Created_On": "2020-05-03T00:00:00.000-05:00",
      "Changed_On": "2020-05-03T00:00:00.000-05:00",
      "Promotions_Applied_Customer": "",
      "DEX_ROW_TS": "2020-05-03T00:00:00.000-05:00",
      "On_Order_Amount": 0.0,
      "Credit_Limit_Type": 0,
      "Finance_Charge_Type": 0,
      "Finance_Charge_Amt": 0.0,
      "Finance_Charge_Pct": 0,
      "Min_Pmt_Type": 0,
      "Min_Pmt_Amt": 0.0,
      "Min_Pmt_Pct": 0,
      "Balance_Type": 0,
      "Max_Writeoff_Type": 0,
      "Max_Writeoff_Amt": 0.0,
      "UserFieldData": [
        "",
        "1900-01-01T00:00:00.000Z",
        "",
        false,
        false,
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        false,
        "",
        "",
        false,
        "",
        "",
        0,
        "",
        false,
        false,
        0
      ],
      "UserFieldNames": [
        "x99SalesRep",
        "xAccount_Start_Date",
        "xBDA",
        "xCAD_Block",
        "xCatalog",
        "xCC_Auth_Form",
        "xCust_Attachment1",
        "xCust_Attachment2",
        "xCust_Attachment3",
        "xCust_Attachment4",
        "xCust_Attachment5",
        "xCustomer_List",
        "xCustomerDiscount",
        "xCustRank",
        "xDeclinedCatalogs",
        "xDiscountOnLoad",
        "xLead_Source",
        "xMCCatalog",
        "xOpenOpportunity",
        "xPayTerms",
        "xPotentialYr",
        "xSalesRepAssigned",
        "xSpecifier",
        "xTechCatalog",
        "xTransactionsPerYr"
      ],
      "Notifications": []
    }

### GET api/Customer/<customer_num> : api/Customer/001624

Response:

    {
      "Customer_Num": "001624         ",
      "Customer_Name": "Logan Test                                                       ",
      "Customer_Class": "RETAIL         ",
      "Corporate_Customer_Num": "               ",
      "Short_Name": "               ",
      "Statement_Name": "                                                                 ",
      "Primary_Addr_Code": "PRIMARY        ",
      "Primary_Bill_To_Addr_Code": "PRIMARY        ",
      "Primary_Ship_To_Addr_Code": "PRIMARY        ",
      "Statement_To_Addr_Code": "PRIMARY        ",
      "Sales_Person_ID": "               ",
      "Sales_Territory": "               ",
      "Payment_Terms": "PREPAID              ",
      "Shipping_Method": "BESTWAY        ",
      "Price_Level": "RETAIL     ",
      "User_Def_1": "                     ",
      "User_Def_2": "                     ",
      "Tax_Exempt_1": "                         ",
      "Tax_Exempt_2": "                         ",
      "Tax_Registration_Num": "                         ",
      "Comment_1": "                               ",
      "Comment_2": "                               ",
      "IntegrationSource": 0,
      "Inactive": false,
      "On_Hold": false,
      "Note": "",
      "Currency_ID": "Z-US$          ",
      "Currency_Dec": 2,
      "Last_Aged": "1900-01-01T00:00:00.000",
      "Balance": 0.00000,
      "Unapplied_Amount": null,
      "Customer_Credit_Limit": 0.0,
      "Last_Pay_Date": "1900-01-01T00:00:00.000",
      "Last_Pay_Amt": 0.00000,
      "First_Invoice_Date": "1900-01-01T00:00:00.000",
      "Last_Invoice_Date": "1900-01-01T00:00:00.000",
      "Last_Invoice_Amt": 0.00000,
      "Last_Stmt_Date": "1900-01-01T00:00:00.000",
      "Last_Stmt_Amt": 0.00000,
      "Life_Avg_Days": 0,
      "Year_Avg_Days": 0,
      "Total_Amt_NSF_Checks_YTD": 0.00000,
      "Num_NSF_Checks_YTD": 0,
      "Tax_Schedule": "AVATAX         ",
      "Ship_Complete": false,
      "Stmt_Email_To": null,
      "Stmt_Email_CC": null,
      "Stmt_Email_BCC": null,
      "Email_To": null,
      "Email_CC": null,
      "Email_BCC": null,
      "Message": "",
      "USERDEF1": "                     ",
      "USERDEF2": "                     ",
      "Trade_Discount": 0.000000000,
      "Master_Distributor": "",
      "Method_Of_Billing": 0,
      "Send_Email_Statements": 0,
      "Created_On": "2020-05-04T00:00:00.000",
      "Changed_On": "1900-01-01T00:00:00.000",
      "Promotions_Applied_Customer": "",
      "DEX_ROW_TS": "2020-05-04T04:38:47.067",
      "On_Order_Amount": 0.00000,
      "Credit_Limit_Type": 1,
      "Finance_Charge_Type": 0,
      "Finance_Charge_Amt": 0.0,
      "Finance_Charge_Pct": 0,
      "Min_Pmt_Type": 0,
      "Min_Pmt_Amt": 0.0,
      "Min_Pmt_Pct": 0,
      "Balance_Type": 0,
      "Max_Writeoff_Type": 0,
      "Max_Writeoff_Amt": 0.0,
      "UserFieldData": [
        "",
        "1900-01-01T00:00:00.000",
        "",
        false,
        false,
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        "",
        false,
        "",
        "",
        false,
        "",
        "",
        0,
        "",
        false,
        false,
        0
      ],
      "UserFieldNames": [
        "x99SalesRep",
        "xAccount_Start_Date",
        "xBDA",
        "xCAD_Block",
        "xCatalog",
        "xCC_Auth_Form",
        "xCust_Attachment1",
        "xCust_Attachment2",
        "xCust_Attachment3",
        "xCust_Attachment4",
        "xCust_Attachment5",
        "xCustomer_List",
        "xCustomerDiscount",
        "xCustRank",
        "xDeclinedCatalogs",
        "xDiscountOnLoad",
        "xLead_Source",
        "xMCCatalog",
        "xOpenOpportunity",
        "xPayTerms",
        "xPotentialYr",
        "xSalesRepAssigned",
        "xSpecifier",
        "xTechCatalog",
        "xTransactionsPerYr"
      ],
      "Notifications": []
    }

### GET api/CustomerAddr/001624/AddressCode

Response:

    [
      {
        "AddressCode": "PRIMARY        "
      },
      {
        "AddressCode": "SECONDARY      "
      }
    ]

### POST api/CustomerAddr

Request:

    {
        "Customer_Num" : "001624",
        "Email" : "logan.montgomery@capgemini.com",
        "Alt_Company_Name" : "Lyonscg",
        "Contact_Person" : "Logan Montgomery",
        "Address_Code" : "PRIMARY",
        "Tax_Schedule" : "AVATAX",
        "Shipping_Method" : "BESTWAY",
        "Modified_On" : "2020-05-03T22:46:28-06:00",
        "Created_On" : "2020-05-03T22:46:28-06:00",
        "UserFieldData" : [],
        "UserFieldNames" : [],
        "Notifications" : []
    }

Response:

    {
      "Customer_Num": "001624",
      "Address_Code": "SECONDARY",
      "Address_Use": "",
      "Sales_Person_ID": "",
      "Shipping_Method": "BESTWAY",
      "UPS_Zone": "",
      "Tax_Schedule": "AVATAX",
      "Alt_Company_Name": "Lyonscg",
      "Contact_Person": "Logan Montgomery Secondary",
      "Address_Line_1": "",
      "Address_Line_2": "",
      "Address_Line_3": "",
      "Country": "",
      "Country_Code": "",
      "City": "",
      "State": "",
      "Zip": "",
      "Phone_1": "",
      "Phone_2": "",
      "Phone_3": "",
      "Fax": "",
      "Modified_On": "2020-05-03T23:48:28.000-05:00",
      "Created_On": "2020-05-03T23:48:28.000-05:00",
      "Comment": "",
      "Email": "logan.montgomery@capgemini.com",
      "Email_To": "",
      "Email_CC": "",
      "Email_BCC": "",
      "Web_Site": "",
      "Login": "",
      "Password": "",
      "Warehouse_Code": "",
      "USERDEF1": "",
      "USERDEF2": "",
      "Dont_Email": false,
      "Dont_Mail": false,
      "Sales_Territory": "",
      "Dont_Fax": false,
      "Address_Validated": false,
      "Address_Classification": "",
      "File_Location_1": "",
      "File_Location_2": "",
      "DEX_ROW_TS": "2020-05-03T00:00:00.000-05:00",
      "UserFieldData": [
        "",
        "",
        false,
        false,
        ""
      ],
      "UserFieldNames": [
        "International_Phone_Num",
        "Time_Zone",
        "xMarketingDoNotEMail",
        "xResidential",
        "xTaxOverride"
      ],
      "Notifications": []
    }

### GET api/CustomerAddr/<customer num>/<address code> : api/CustomerAddr/001624/PRIMARY

Response:

    {
      "Customer_Num": "001624         ",
      "Address_Code": "PRIMARY        ",
      "Address_Use": "",
      "Sales_Person_ID": "               ",
      "Shipping_Method": "BESTWAY        ",
      "UPS_Zone": "   ",
      "Tax_Schedule": "AVATAX         ",
      "Alt_Company_Name": "Lyonscg                                                          ",
      "Contact_Person": "Logan Montgomery                                             ",
      "Address_Line_1": "                                                             ",
      "Address_Line_2": "                                                             ",
      "Address_Line_3": "                                                             ",
      "Country": "                                                             ",
      "Country_Code": "       ",
      "City": "                                   ",
      "State": "                             ",
      "Zip": "           ",
      "Phone_1": "                     ",
      "Phone_2": "                     ",
      "Phone_3": "                     ",
      "Fax": "                     ",
      "Modified_On": "1900-01-01T00:00:00.000",
      "Created_On": "2020-05-04T00:00:00.000",
      "Comment": "",
      "Email": "logan.montgomery@capgemini.com                                                                                                                                                                           ",
      "Email_To": "",
      "Email_CC": "",
      "Email_BCC": "",
      "Web_Site": "                                                                                                                                                                                                         ",
      "Login": "                                                                                                                                                                                                         ",
      "Password": "                                                                                                                                                                                                         ",
      "Warehouse_Code": "           ",
      "USERDEF1": "                     ",
      "USERDEF2": "                     ",
      "Dont_Email": false,
      "Dont_Mail": false,
      "Sales_Territory": "               ",
      "Dont_Fax": false,
      "Address_Validated": false,
      "Address_Classification": "",
      "File_Location_1": "                                                                                                                                                                                                         ",
      "File_Location_2": "                                                                                                                                                                                                         ",
      "DEX_ROW_TS": "2020-05-04T04:47:04.043",
      "UserFieldData": [
        "",
        "",
        false,
        false,
        ""
      ],
      "UserFieldNames": [
        "International_Phone_Num",
        "Time_Zone",
        "xMarketingDoNotEMail",
        "xResidential",
        "xTaxOverride"
      ],
      "Notifications": []
    }
