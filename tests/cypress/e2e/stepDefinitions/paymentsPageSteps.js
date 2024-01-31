const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import PaymentsPage from '../pageObjects/paymentsPage';
const paymentsPage = new PaymentsPage();

Then('user should be able to see below available headers of payments list', (dataTable) => {
    const expectedListHeaders = dataTable.raw().slice(1).map((row) => row[0]).toString().replace(/\s+/g, '');

    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate available headers of list  
            paymentsPage.getListHeaders().then((actualListHeaders) => {
                actualListHeaders = actualListHeaders.replace(/\s+/g, '');
                expect(actualListHeaders.toLowerCase()).to.eq(expectedListHeaders.toLowerCase());    
            });
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('the {string} value of account dropdown should be selected by default', (expectedDefaultSelectedValue) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate the default value of account dropdown
            paymentsPage.getAccountDropdown().then((actualDefaultSelectedValue) => {
                expect(actualDefaultSelectedValue.toLowerCase()).to.eq(expectedDefaultSelectedValue.toLowerCase());    
            });
        } else {
            cy.log('The account dropdown is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user enter {string} in search box', (number) => {
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') { 
            //enter the number in search box
            paymentsPage.enterNumberInSearchBox(number);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below check number in search results', (dataTable) => {
    const expectedNumber = dataTable.raw().slice(1).map((row) => row[0]).toString();
    let isFound = 'no';
   
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') {
            //get all numbers from the search results
            paymentsPage.getCheckNumbersList().then(($list) => {
                $list.each((index, $el) => {
                    let actualNumber = Cypress.$($el).text().trim();

                    //check expected number in the search results
                    if (expectedNumber === actualNumber) {
                        isFound = 'yes';
                    }
                });

                //validate whether the expected number is found or not in the search results
                if(isFound === 'yes'){
                    cy.log(expectedNumber + ' number is found in the search results');
                } else {
                    throw new Error(expectedNumber + ' number is not found in the search results');
                }
            });           
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below payment number in search results', (dataTable) => {
    const expectedNumber = dataTable.raw().slice(1).map((row) => row[0]).toString();
    let isFound = 'no';
   
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') {
            //get all numbers from the search results
            paymentsPage.getPaymentNumbersList().then(($list) => {
                $list.each((index, $el) => {
                    let actualNumber = Cypress.$($el).text().trim();

                    //check expected number in the search results
                    if (expectedNumber === actualNumber) {
                        isFound = 'yes';
                    }
                });

                //validate whether the expected number is found or not in the search results
                if(isFound === 'yes'){
                    cy.log(expectedNumber + ' number is found in the search results');
                } else {
                    throw new Error(expectedNumber + ' number is not found in the search results');
                }
            });           
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});