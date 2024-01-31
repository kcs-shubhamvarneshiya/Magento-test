const searchButton = 'div.search_button > button[type="submit"]';
const searchText = 'div.column.main > span > p';
const searchBox = 'textarea#searchbox';
const searchResultsRows = 'table > tbody > tr';
const searchResultsSKUCodes = 'table > tbody > tr > td:nth-child(2)';
const requestToOrderLink = 'div.request-to-order-link > a';
const addToCartButton = 'div.actions-toolbar button[title="Add to Cart"]';
const productQty = 'input[type="number"]';
const successMsg = 'div.page.messages div[data-ui-id="message-success"] > div';
const productName = 'td:nth-child(3)';

class InventoryPage {

    getSearchButton () {
       return cy.get(searchButton).contains('search', {matchCase: false});
    } 

    getSearchText () {
        return cy.get(searchText);
    } 

    enterSKU (value) {
        cy.get(searchBox).should('be.visible').clear().type(value);
    }

    clickSearchButton () {
        cy.get(searchButton).should('be.visible').click();
    }

    getSearchResultsRows () {
        return cy.get(searchResultsRows).then((numberOfRows) => {
            return numberOfRows.length-1;
        });
    }

    getSearchResultsSKUCodes () {
        return cy.getListElement(searchResultsSKUCodes, ",");
    }

    getImageOfSearchedSKU (SKUValue) {
        return cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings('td')
        .children('img').scrollIntoView();
    }

    getRequestToOrderLink (SKUValue) {
        return cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings('td')
        .find(requestToOrderLink).scrollIntoView();
    }
    
    getAddToCartButton () {
        return cy.get(addToCartButton);
    }

    clickRequestToOrderLink (SKUValue) {
        cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings('td').find(requestToOrderLink)
        .invoke('removeAttr','target').should('be.visible').click();
    }

    enterProductQty (qty, SKUValue) {
        cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings('td').find(productQty)
        .should('be.visible').clear().type(qty);
    }

    clickAddToCartButton () {
        cy.wait(5000); //added static wait because of js load issue
        cy.get(addToCartButton).should('be.visible').click();
    }

    getSuccessMessage () {
        return cy.get(successMsg).should('be.visible').then((displayedMessage) => {
            return displayedMessage.text().trim();
        });
    }

    getPageTitle () {
        cy.waitForSometime();
        return cy.title();
    }

    getProductQty (SKUValue) {
        return cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings('td').find(productQty)
        .should('be.visible').invoke('val');
    }

    getProductName (SKUValue) {
        return cy.contains(searchResultsSKUCodes, SKUValue, { matchCase: false }).siblings(productName).then((displayedName) => {
            return displayedName.text().trim();
        });
    }

} export default InventoryPage