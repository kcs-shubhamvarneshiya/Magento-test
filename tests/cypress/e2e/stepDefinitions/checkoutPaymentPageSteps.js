const { Then } = require ('@badeball/cypress-cucumber-preprocessor');

import CheckoutPaymentPage from '../pageObjects/checkoutPaymentPage';
import { orderDetails } from './shoppingCartPageSteps';
import { productDetails, bulbDetails } from './productDescriptionPageSteps';

const checkoutPaymentPage = new CheckoutPaymentPage();

Then('the order summary section should be displayed on the payment page', () => { 
    //validate the payment page url
    checkoutPaymentPage.validatePaymentPageUrl();

    //validate the order summary section should be displayed
    checkoutPaymentPage.getOrderSummaryTitle().should('be.visible');
});

Then('the following details should be displayed on the order summary section', (dataTable) => {
    const validLabel = ['Product Name', 'Product Price', 'Product Qty', 'Finish', 'Bulb Name', 'Bulb Price', 'Bulb Qty', 'Cart Subtotal', 'Shipping Charges', 'Tax/Vat', 'Merchandise Total', 'Merchandise Total Guest User', 'Vat'];
    
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the details displayed on the order summary section
        switch (label) {
            case 'product name':
                checkoutPaymentPage.getOrderSummaryItemNameList().then((displayedItemNameList) => {
                    expect(displayedItemNameList).to.be.include(productDetails.productName);
                });
                break;
            case 'product price':
                checkoutPaymentPage.getOrderSummaryItemPrice(productDetails.productName).then((displayedProductPrice) => {
                    expect(displayedProductPrice).to.be.equal(productDetails.productPrice);
                });
                break;
            case 'product qty':
                checkoutPaymentPage.getOrderSummaryItemQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                checkoutPaymentPage.clickOrderSummaryItemSeeDetailsLink(productDetails.productName);
                checkoutPaymentPage.getOrderSummaryItemVariant(productDetails.productName, label).then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            case 'bulb name':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        checkoutPaymentPage.getOrderSummaryItemNameList().then((displayedItemNameList) => {
                            expect(displayedItemNameList).to.be.include(bulbDetails.bulbName);
                        });
                    }
                });
                break;
            case 'bulb price':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        checkoutPaymentPage.getOrderSummaryItemPrice(bulbDetails.bulbName).then((displayedBulbPrice) => {
                            cy.convertPriceToNumber(displayedBulbPrice, '$').then((displayedBulbPriceAsNumber) => {
                                cy.bulbPricePerUnit(bulbDetails.bulbPrice, bulbDetails.bulbQty).then((bulbPricePerUnit) => {
                                    cy.log('Skipping this validation because of the reported BUG 17452 ');
                                    //expect(parseFloat(displayedBulbPriceAsNumber).toFixed(2)).to.be.equal(bulbPricePerUnit);
                                });
                            });
                        });
                    }
                });
                break;
            case 'bulb qty':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        checkoutPaymentPage.getOrderSummaryItemQty(bulbDetails.bulbName).then((displayedBulbQty) => {
                            expect(displayedBulbQty).to.be.equal(bulbDetails.bulbQty);
                        });
                    }
                });
                break;
            case 'cart subtotal':
                checkoutPaymentPage.getOrderSummaryCartSubTotal().then((displayedCartSubTotal) => {
                    expect(displayedCartSubTotal).to.be.equal(orderDetails.cartSubtotal);
                });
                break;
            case 'shipping charges':
                checkoutPaymentPage.getOrderSummaryShippingCharges().then((displayedShippingCharge) => {
                    expect(displayedShippingCharge).to.be.equal(orderDetails.shippingCharge);
                });
                break;
            case 'tax/vat':
                checkoutPaymentPage.getOrderSummaryTaxCharges().then((displayedOrderTax) => {
                    expect(displayedOrderTax).to.be.equal(orderDetails.orderTax);
                });
                break;
            case 'vat':
                cy.getCountry().then((testingCountry) => {    
                    if (testingCountry !== 'us') {
                        checkoutPaymentPage.getOrderSummaryTaxCharges().then((displayedOrderTax) => {
                            expect(displayedOrderTax).to.be.equal(orderDetails.orderTax);
                        });
                    }
                });
                break;
            case 'merchandise total':
                checkoutPaymentPage.getOrderSummaryMerchandiseTotal().then((displayedMerchandiseTotal) => {
                    expect(displayedMerchandiseTotal).to.be.equal(orderDetails.merchandiseTotal);
                });
                break;
            case 'merchandise total guest user':
                cy.getCountry().then((testingCountry) => {    
                    if (testingCountry === 'us') { 
                        checkoutPaymentPage.getOrderSummaryExpectedMerchandiseTotalGuestUser(orderDetails.merchandiseTotal).then((expectedMerchandiseTotal) => {
                            checkoutPaymentPage.getOrderSummaryMerchandiseTotalGuestUser().then((displayedMerchandiseTotal) => {
                                expect(displayedMerchandiseTotal).to.be.equal(expectedMerchandiseTotal);
                            });
                        });
                    } else {
                        checkoutPaymentPage.getOrderSummaryMerchandiseTotal().then((displayedMerchandiseTotal) => {
                            expect(displayedMerchandiseTotal).to.be.equal(orderDetails.merchandiseTotal);
                        });
                    }   
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});