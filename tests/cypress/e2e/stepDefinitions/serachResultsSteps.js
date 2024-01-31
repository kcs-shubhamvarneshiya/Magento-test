const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import HeaderPage from '../pageObjects/headerPage';
import SearchResultsPage from '../pageObjects/serachResultsPage';

const header = new HeaderPage();
const search = new SearchResultsPage();

When('user press enter key', () => {
    //press enter key
    header.pressEnterKey();
});

Then('search results page of {string} should get opened', (searchText) => {
    //validate search results page url
    search.getSearchPageUrl().then((currentUrl) => {
        expect(currentUrl.endsWith(searchText), 'Navigate to url - ' + currentUrl).to.be.true;
    });   
});

Then('search results text should contain {string}', (searchText) => {
    //validate whether the search results contain search text or not
    search.getSearchResultsText().should('contain.text', searchText);
});

Then('{int} products should be displayed on the first page of search results', (numberOfProducts) => {
    //validate 48 products on the search results page
    search.getProductList().should('have.length',numberOfProducts);
});

When('user navigate to the search results page of {string}', (searchText) => {
    //validate search results page url
    search.getSearchPageUrl().then((currentUrl) => {
        expect(currentUrl.endsWith(searchText), 'Navigate to url - ' + currentUrl).to.be.true;
    }); 
}); 

Then('the following product details should be displayed on the product card of {string}', (skuCode, dataTable) => {
    const validLabel = ['Product Name', 'Designer Name', 'SKU Code', 'Price', 'Original Price', 'View More Link'];

    //find the given product code
    cy.findProductCode(skuCode);

    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const label = dataTable.rawTable[i][1].toLowerCase();
            const expectedValue = dataTable.rawTable[i][2];          
  
            //validate product details displayed on the product card
            if (testingCountry === country) {
                switch (label) {
                    case 'product name':
                        cy.validateDisplayedText(search.getProductName(skuCode), expectedValue);
                        break;
                    case 'designer name':
                        cy.validateDisplayedText(search.getDesignerName(skuCode), expectedValue);
                        break;
                    case 'sku code':
                        cy.validateDisplayedText(search.getProductCode(skuCode), expectedValue);
                        break;
                    case 'price':
                        search.getProductSalePrice(skuCode).should('be.visible');
                        break;               
                    case 'original price':
                        search.getProductOriginalPrice(skuCode).should('be.visible');
                        break;
                    case 'view more link':
                        cy.validateDisplayedText(search.getProductViewMoreLink(skuCode), expectedValue);
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
                }
            }
        }
    });
});

Then('pagination should be available at the bottom of page', () => {
    //validate whether the pagination is available or not
    search.getPagination().should('exist').should('be.visible');
});

When('user search the following sku code', (dataTable) => {
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const skuCode = row.skuCode;

            if (testingCountry === country) {
                //enter the sku in search box
                header.enterSearchText(skuCode);
            }
        });
    });
});