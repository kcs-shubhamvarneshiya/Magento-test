const thankYou = '.csp-h1';
const productShipByDate = 'div.product-item-details > div.product-stock';
const productNameList = 'td[data-th="Product"] > div.product-item-details > strong.product-item-name';
const productDetails = 'tbody.cart.item';

class CheckoutSuccessPage {

    getThankYouText () {
       return cy.get(thankYou);
    } 

    getProductShipByDate (productName) {
        cy.contains(productNameList, productName, { matchCase: false }).parents(productDetails).find(productShipByDate)
        .should('be.visible').should('include.text', 'ship').should('include.text', 'by').as('itemShipByDate');

        return cy.get('@itemShipByDate').then((displayedShipByDateText) => {
            const shipByDate = displayedShipByDateText.text().trim().match(/(\d{2}\.\d{2}\.\d{2})/);
            return shipByDate[0];
        });
    }

} export default CheckoutSuccessPage