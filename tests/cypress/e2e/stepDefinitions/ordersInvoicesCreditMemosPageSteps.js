const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import OrdersInvoicesCreditMemosPage from '../pageObjects/ordersInvoicesCreditMemosPage';
const selfServicePage = new OrdersInvoicesCreditMemosPage();

When('user click on the {string} header', (headerName) => {
    //click on the given header
    selfServicePage.clickHeader(headerName);
});

Then('user should be able to see the below placeholder for the search box', (dataTable) => {
    const expectedPlaceholder = dataTable.rawTable[1].toString();

    //validate the placeholder of search box
    selfServicePage.getSearchBoxPlaceholder().then((actualPlaceholder) => {
        expect(actualPlaceholder).to.be.equal(expectedPlaceholder.toLowerCase());
    });
});

Then('user should be able to see {string} header as selected', (expectedSelectedHeader) => {
    //validate the selected header name
    selfServicePage.getSelectedHeader().then((actualSelectedHeader) => {
        expect(actualSelectedHeader).to.be.equal(expectedSelectedHeader.toLowerCase());
    });
});

Then('the pagination should be available at the bottom of page', () => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate whether the pagination is available or not
            selfServicePage.getPagination().should('exist').should('be.visible');
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see {int} as default pagination', (expectedPaginationOption) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate the default selected option of pagination
            selfServicePage.getSelectedPaginationDropdownOption().then((actualSelectedPaginationOption) => {
                expect(actualSelectedPaginationOption).to.eq(expectedPaginationOption.toString());  
            });
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below available pagination options', (dataTable) => {
    const expectedDropdownOptions = dataTable.raw().slice(1).map((row) => row[0]).toString();

    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate available dropdown options
            selfServicePage.getPaginationDropdownOptions().then((actualDropdownOptions) => {
                expect(actualDropdownOptions).to.eq(expectedDropdownOptions);  
	        });
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below available headers of list', (dataTable) => {
    const expectedListHeaders = dataTable.raw().slice(1).map((row) => row[0]).toString().replace(/\s+/g, '');

    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate available headers of list  
            selfServicePage.getListHeaders().then((actualListHeaders) => {
                actualListHeaders = actualListHeaders.replace('Open', '').replace('Closed', '').replace('Partial', '').replace(/\s+/g, '');
                expect(actualListHeaders.toLowerCase()).to.eq(expectedListHeaders.toLowerCase());    
            });
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user click on the view link of {string} number', (number) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //click on the view link of given number
            selfServicePage.clickViewLinkOfNumber(number);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('details page of {string} number should get opened', (number) => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate whether the page title contains the given number or not
            selfServicePage.getPageTitle().should('be.visible').should('contain.text', number);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below available headers on the page', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedHeaders = dataTable.rawTable[i][1].toString().replace(/ /g, '');

            if (testingCountry === country) {
                //validate the headers displayed on the page
                selfServicePage.getTopHeaders().then((actualHeaders) => {
                    expect(actualHeaders.toLowerCase().replace(/\s+/g, '')).to.eq(expectedHeaders.toLowerCase());                     
                });
                break;  
            }
        }
    });
});

When('user enter {int} number in search box', (number) => {
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') { 
            //enter the number in search box
            selfServicePage.enterNumberInSearchBox(number);
        } else {
            cy.log('The test data is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should be able to see below number in search results', (dataTable) => {
    const expectedNumber = dataTable.raw().slice(1).map((row) => row[0]).toString();
    let isFound = 'no';
   
    cy.getCountry().then((testingCountry) => {       
        if (testingCountry === 'us') {
            //get all numbers from the search results
            selfServicePage.getNumbersList().then(($list) => {
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

When('user should be able see search box', () => {
    //validate whether the search box is available or not
    selfServicePage.getSearchBox().should('exist').should('be.visible');
});

When('user click on the filter by', () => {
    //click on the filter by
    selfServicePage.clickFilterBy();
});

Then('the {string} filter should be displayed', (filterName) => {
    //validate whether the given filter is displayed or not
    selfServicePage.getFilter(filterName).should('be.visible');
});

Then('user should be able to see below message if the orders are not available', (dataTable) => {
    const expectedMessage = dataTable.rawTable[1].toString();
  
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() === 'production') {
            //validate the no orders message should be displayed
            selfServicePage.getNoOrdersMessage().then((displayedMessage) => {
                expect(displayedMessage.toLowerCase()).to.eq(expectedMessage.toLowerCase());
            });
        } else {
            cy.getCountry().then((testingCountry) => {       
                if (testingCountry === 'us') {
                    //validate the orders list table should be displayed
                    selfServicePage.getOrdersList().should('be.visible');
                } else {
                    //validate the no orders message should be displayed
                    selfServicePage.getNoOrdersMessage().then((displayedMessage) => {
                        expect(displayedMessage.toLowerCase()).to.eq(expectedMessage.toLowerCase());
                    });
                }
            });
        }
    });
});