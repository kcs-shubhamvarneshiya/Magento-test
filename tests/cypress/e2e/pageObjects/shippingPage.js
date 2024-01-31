const shippingMethod = '.col-method-title > span';
const addressLine1 = 'input[name="street[0]"]';
const zipCode = 'input[name="postcode"]';
const continueToPayment = '.checkout-next-btn-label > span';
const continueToPaymentLabel = 'Continue to Payment';
const phoneNumber = 'input[name="telephone"]';
const city = 'input[name="city"]';
const email = '#customer-email-fieldset > .required > .control > #customer-email';
const firstName = 'input[name="firstname"]';
const lastName = 'input[name="lastname"]';
const country = 'select[name="country_id"]';
const state = 'select[name="region_id"]';
const shippingPageTitle = 'Checkout';
const requestDeliveryDate = 'input[name="delivery_date"]';
const stateField = 'input[name="region"]';
const orderSummaryTitle = 'div.opc-block-summary > span.title';
const orderSummaryItemDetails = 'li.product-item';
const orderSummaryItemNameList = 'strong.product-item-name';
const orderSummaryItemPrice = 'span.cart-price > span.price';
const orderSummaryItemQty = 'div.details-qty > span.value';
const orderSummarySeeDetailsLink = 'div.product.options > span.toggle';
const orderSummaryItemVariant = 'dd.values';

class ShippingPage {

    getPageTitle () {
        return cy.title();
    }

    selectShippingMethod (value) {
        cy.get(shippingMethod).contains(value, {matchCase: false}).scrollIntoView().click();
    }

    clickContinueToPaymentButton () {
        cy.get(continueToPayment).contains(continueToPaymentLabel, {matchCase: false}).click();
    }

    enterAddressLine1 (value) {
        cy.get(addressLine1).should('be.visible').clear().type(value);
    }

    enterZipCode (value) {
        cy.get(zipCode).should('be.visible').clear().type(value);
    }

    enterPhoneNumber (value) {
        cy.get(phoneNumber).should('be.visible').clear().type(value);
    }

    enterCity (value) {
        cy.get(city).should('be.visible').clear().type(value);
    }

    enterEmail (value) {
        cy.get(email).should('be.visible').clear().type(value);
    }

    enterFirstName (value) {
        cy.get(firstName).should('be.visible').clear().type(value);
    }

    enterLastName (value) {
        cy.get(lastName).should('be.visible').clear().type(value);
    }

    selectCountry (value) {
        cy.get(country).should('be.visible').select(value);
    }

    selectState (value) {
        cy.get(state).should('be.visible').select(value);
    }

    enterState (value) {
        cy.get(stateField).clear().type(value);
    }

    validateShippingPageTitle () {
        this.getPageTitle().should('contain', shippingPageTitle);
    }

    selectRequestDeliveryDate (days) {
        cy.addDaysToCurrentDate(days, '-').then((date) => {
            cy.get(requestDeliveryDate).type(date, {force: true});
        });
    }

    getShippingPageUrl () {
        cy.waitForOrderSummaryLoad();
        return cy.url();
    }

    getOrderSummaryTitle () {
        return cy.get(orderSummaryTitle).contains('Order Summary', {matchCase: false});
    }

    getOrderSummaryItemNameList () {
        return cy.get(orderSummaryItemNameList).should('be.visible').then((displayedItemNameList) => {
            return displayedItemNameList.text().trim().toLowerCase();
        });
    }

    getOrderSummaryItemPrice (itemName) {
        return cy.contains(itemName, { matchCase: false }).parents(orderSummaryItemDetails).find(orderSummaryItemPrice)
        .should('be.visible').then((displayedItemPrice) => {
            return displayedItemPrice.text().trim();
        });
    }

    getOrderSummaryItemQty (itemName) {
        return cy.contains(itemName, { matchCase: false }).parents(orderSummaryItemDetails).find(orderSummaryItemQty)
        .should('be.visible').then((displayedItemQty) => {
            return displayedItemQty.text().trim();
        });
    }

    clickOrderSummaryItemSeeDetailsLink (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(orderSummaryItemDetails).find(orderSummarySeeDetailsLink)
        .click();
    }

    getOrderSummaryItemVariant (itemName, variant) {
        return cy.contains(itemName, { matchCase: false }).parents(orderSummaryItemDetails).contains(variant, {matchCase: false})
        .next(orderSummaryItemVariant).then((displayedItemVariant) => {
            return displayedItemVariant.text().trim().toLowerCase();
        });
    }

} export default ShippingPage