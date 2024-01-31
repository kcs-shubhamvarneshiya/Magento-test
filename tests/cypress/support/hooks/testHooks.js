//run before all test cases
before(() => {
    //handle uncaught exceptions to make execution smooth
    Cypress.on('uncaught:exception', (err, runnable) => {
        // returning false here prevents Cypress from failing the test
        return false;
    }); 
});

//run before each test case
beforeEach(() => {
    //do disable logs of fetch/XHR type APIs
    cy.intercept({ resourceType: /xhr|fetch/ }, { log: false });

    //clear all cookies and local storage
    cy.clearAllCookies();
    cy.clearAllLocalStorage();
});