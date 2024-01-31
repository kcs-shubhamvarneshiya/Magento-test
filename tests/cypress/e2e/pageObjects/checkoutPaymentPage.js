const paymentPageUrl = 'checkout/#payment';
const orderSummaryTitle = 'div.opc-block-summary > span.title';
const orderSummaryItemDetails = 'li.product-item';
const orderSummaryItemNameList = 'strong.product-item-name';
const orderSummaryItemPrice = 'span.cart-price > span.price';
const orderSummaryItemQty = 'div.details-qty > span.value';
const orderSummarySeeDetailsLink = 'div.product.options > span.toggle';
const orderSummaryItemVariant = 'dd.values';
const orderSummaryCartSubTotal = 'tr.totals.sub > td.amount > span.price';
const orderSummaryShippingCharges = 'tr.totals.shipping.excl > td.amount > span.price';
const orderSummaryTaxCharges = 'tr.totals-tax > td.amount > span.price';
const orderSummaryMerchandiseTotal = 'tr.grand.totals > td.amount span.price';

class CheckoutPaymentPage {

    validatePaymentPageUrl () {
        cy.url().should('contain', paymentPageUrl);
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
        .click({force: true});
    }

    getOrderSummaryItemVariant (itemName, variant) {
        return cy.contains(itemName, { matchCase: false }).parents(orderSummaryItemDetails).contains(variant, {matchCase: false})
        .next(orderSummaryItemVariant).then((displayedItemVariant) => {
            return displayedItemVariant.text().trim().toLowerCase();
        });
    }

    getOrderSummaryCartSubTotal () {
        return cy.get(orderSummaryCartSubTotal).should('be.visible').scrollIntoView().then((displayedCartSubTotal) => {
            return displayedCartSubTotal.text().trim();
        });
    }

    getOrderSummaryShippingCharges () {
        return cy.get(orderSummaryShippingCharges).should('be.visible').then((displayedShippingCharges) => {
            return displayedShippingCharges.text().trim();
        });
    }

    getOrderSummaryTaxCharges () {
        return cy.get(orderSummaryTaxCharges).should('be.visible').then((displayedTaxCharges) => {
            return displayedTaxCharges.text().trim();
        });
    }

    getOrderSummaryMerchandiseTotal () {
        return cy.get(orderSummaryMerchandiseTotal).should('be.visible').then((displayedMerchandiseTotal) => {
            return displayedMerchandiseTotal.text().trim();
        });
    }

    getOrderSummaryExpectedMerchandiseTotalGuestUser (shoppingCartMerchandiseTotal) {
        return this.getOrderSummaryTaxCharges().then((displayedTaxCharges) => {
            return cy.getCountry().then((testingCountry) => {    
                if (testingCountry === 'us') {
                    return cy.convertPriceToNumber(displayedTaxCharges, '$').then((taxAmount) => {
                        return cy.convertPriceToNumber(shoppingCartMerchandiseTotal, '$').then((merchandiseTotalAmount) => {
                            return (parseFloat(merchandiseTotalAmount + taxAmount).toFixed(2));
                        });
                    });
                } else if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedTaxCharges, '£').then((taxAmount) => {
                        return cy.convertPriceToNumber(shoppingCartMerchandiseTotal, '£').then((merchandiseTotalAmount) => {
                            return (parseFloat(merchandiseTotalAmount + taxAmount).toFixed(2));
                        });
                    });
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedTaxCharges, '€').then((taxAmount) => {
                        return cy.convertPriceToNumber(shoppingCartMerchandiseTotal, '€').then((merchandiseTotalAmount) => {
                            return (parseFloat(merchandiseTotalAmount + taxAmount).toFixed(2));
                        });
                    });
                }
            });
        });
    }

    getOrderSummaryMerchandiseTotalGuestUser () {
        return cy.get(orderSummaryMerchandiseTotal).should('be.visible').then((displayedMerchandiseTotal) => {
            cy.getCountry().then((testingCountry) => {    
                if (testingCountry === 'us') {
                    cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '$').then((merchandiseTotalAmount) => {
                        return (parseFloat(merchandiseTotalAmount).toFixed(2));
                    });
                } else if (testingCountry === 'uk') {
                    cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '£').then((merchandiseTotalAmount) => {
                        return (parseFloat(merchandiseTotalAmount).toFixed(2));
                    });
                } else if (testingCountry === 'eu') {
                    cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '€').then((merchandiseTotalAmount) => {
                        return (parseFloat(merchandiseTotalAmount).toFixed(2));
                    });
                }
            });
        });   
    }

} export default CheckoutPaymentPage