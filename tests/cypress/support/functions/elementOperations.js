import HeaderPage from '../../e2e/pageObjects/headerPage';
import ShoppingCartPage from '../../e2e/pageObjects/shoppingCartPage';

const shoppingCart = new ShoppingCartPage();
const header = new HeaderPage();

//wait for the search box to get displayed
Cypress.Commands.add('waitForSometime', () => {
    header.getSearchTextbox().should('be.visible');
});

//return substring after the split at the given index
Cypress.Commands.add('getSubString', (selector, separator, index) => {
    cy.get(selector) 
    .invoke('text') 
    .then((text) => {
        const parts = text.split(separator);
        const lastValue = parts[index].trim(); 
        return lastValue;
    });
});	

//return list of element with its text
Cypress.Commands.add('getListElement', (selector, separator) => {
    return cy.get(selector).then($list => {
        let elementList = "";
        $list.each((index, $el) => {
            const elementText = Cypress.$($el).text().trim();
            if (index === 0) {
                elementList = elementText;
            } else {
                elementList = elementList.concat(separator + elementText);
            }
        });
        return Promise.all(elementList).then(() => {
            return elementList;
        });
    });
});	

//validate the displayed text
Cypress.Commands.add('validateDisplayedText', (element, expectedText) => {
    element.then((displayedText) => {
        expect(displayedText.text().trim().toLowerCase()).to.be.equal(expectedText.toLowerCase());
    });
});

//return bulb price per unit
Cypress.Commands.add('bulbPricePerUnit', (totalBulbPrice, bulbQty) => {
    return cy.convertStringToNumber(bulbQty).then((qtyAsNumber) => {
        return cy.convertPriceToNumber(totalBulbPrice, '$').then((priceAsNumber) => {
            return parseFloat(priceAsNumber/qtyAsNumber).toFixed(2);
        });
    });
});

//wait for the order summary section to get loaded
Cypress.Commands.add('waitForOrderSummaryLoad', () => {
    shoppingCart.waitForOrderSummaryLoad();
});