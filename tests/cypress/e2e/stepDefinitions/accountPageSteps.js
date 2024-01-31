const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import AccountPage from '../pageObjects/accountPage';
import OrdersInvoicesCreditMemosPage from '../pageObjects/ordersInvoicesCreditMemosPage';

const accountPage = new AccountPage();
const selfServicePage = new OrdersInvoicesCreditMemosPage();

When('user click on the view all link of {string} list', (listName) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //click on the view all link of given list
            accountPage.clickViewAllLink(listName);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('{string} page should get opened', (pageName) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate whether the page header contains given page name or not
            accountPage.getPageHeader().should('have.text', pageName);  
        } else {
            cy.waitForSometime();
            cy.scrollTo('center');
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below header as selected', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedSelectedHeader = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {
                //validate the selected header name
                selfServicePage.getSelectedHeader().then((actualSelectedHeader) => {
                    expect(actualSelectedHeader).to.be.equal(expectedSelectedHeader.toLowerCase());
                });
                break;  
            }
        }
    });
});

When('user click on the {string} link of {string} number', (link, number) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //click on the view link of given number
            accountPage.clickViewLinkOfNumber(link, number);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below account Number', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const accountNumber = dataTable.rawTable[i][1].toString();

            if (testingCountry === country && testingCountry === 'us') {
                //validate the whether account number same or not
                accountPage.getAccountNumber().then((actualAccountNumber) => {
                    expect(actualAccountNumber).to.be.equal(accountNumber.toLowerCase());
                });
                break;  
            } else {
                cy.log('The account number is not available for ' + testingCountry.toUpperCase() + ' country.');
            }
        }
    });
});

Then('user should be able to see below email id', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const emailID = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {
                //validate whether email ID same or not
                accountPage.getEmailID().then((actualEmailID) => {
                    expect(actualEmailID).to.be.equal(emailID.toLowerCase());
                });
                break;  
            }
        }
    });
});

Then('user should be able to see below first name and last name', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const firstName = dataTable.rawTable[i][1].toString();
            const lastName = dataTable.rawTable[i][2].toString();
           
            if (testingCountry === country) {
                //validate the first name and last name
                accountPage.getFirstNameLastName().then((actualName) => {
                    expect(actualName.text().trim().toLowerCase()).contain(firstName.toLowerCase());
                    expect(actualName.text().trim().toLowerCase()).contain(lastName.toLowerCase());
                });
                break;  
            }
        }
    });
});

When('user open the following url', (dataTable) => {
    const url = dataTable.rawTable[1].toString();

    //open the given url
    accountPage.openSelfServicePageUrl(url);
});

Then('user should be able see search box and account dropdown', () => {
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') {
            //validate whether the search box is available or not
            selfServicePage.getSearchBox().should('exist').should('be.visible');
            //validate whether the account dropdown is available or not
            accountPage.getAccountDropdown().should('exist').should('be.visible');           
        } else {
            //validate whether the search box is available or not
            selfServicePage.getSearchBox().should('exist').should('be.visible');
            cy.log('Account dropdown is not available for given country:' + testingCountry.toUpperCase());
        }
    });
});