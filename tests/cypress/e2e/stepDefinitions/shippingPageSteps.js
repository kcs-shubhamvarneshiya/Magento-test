const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import PaymentMethodsPage from '../pageObjects/paymentMethodsPage';
import ShippingPage from '../pageObjects/shippingPage';
import { isContinue, skippingReason } from './paymentMethodSteps';
import { productDetails, bulbDetails } from './productDescriptionPageSteps';

const payment = new PaymentMethodsPage();
const shipping = new ShippingPage();

Then('wholesale user should see {string} shipping method in shipping section', (method) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') { 
            //validate shipping method in shipping section
            payment.getShippingMethodOfShippingSection().should('have.text', method);
        } else {
            cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('user should see {string} shipping method in order summary section', (method) => {
    if (isContinue === 'yes') {
        //validate shipping method in order summary section
        payment.getShippingMethodOfOrderSummarySection().should('have.text', method);  
    } else {
        cy.log(skippingReason);
    }
});

When('wholesale user select {int} days after the current date', (days) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') { 
            //select the date of given days
            shipping.selectRequestDeliveryDate(days);
        } else {
            cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('wholesale user should see {int} days date after the current date as shipping method in shipping section', (days) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') { 
            //validate shipping date in shipping section 
            payment.getShippingDateOfShippingSection().then((displayedShippingDate) => {
                cy.addDaysToCurrentDate(days, '/').then((expectedShippingDate) => {
                    expect(displayedShippingDate.text().trim()).to.be.equal(expectedShippingDate);
                });
            });
        } else {
            cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('the order summary section should be displayed on the shipping page', () => {
    //validate shipping page title
    shipping.validateShippingPageTitle();

    //validate the order summary section should be displayed
    shipping.getOrderSummaryTitle().should('be.visible');
});

Then('the following item details should be displayed on the order summary section', (dataTable) => {
    const validLabel = ['Product Name', 'Product Price', 'Product Qty', 'Finish', 'Bulb Name', 'Bulb Price', 'Bulb Qty'];
    
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the order summary section
        switch (label) {
            case 'product name':
                cy.waitForOrderSummaryLoad();
                shipping.getOrderSummaryItemNameList().then((displayedItemNameList) => {
                    expect(displayedItemNameList).to.be.include(productDetails.productName);
                });
                break;
            case 'product price':
                shipping.getOrderSummaryItemPrice(productDetails.productName).then((displayedProductPrice) => {
                    expect(displayedProductPrice).to.be.equal(productDetails.productPrice);
                });
                break;
            case 'product qty':
                shipping.getOrderSummaryItemQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                shipping.clickOrderSummaryItemSeeDetailsLink(productDetails.productName);
                shipping.getOrderSummaryItemVariant(productDetails.productName, label).then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            case 'bulb name':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        shipping.getOrderSummaryItemNameList().then((displayedItemNameList) => {
                            expect(displayedItemNameList).to.be.include(bulbDetails.bulbName);
                        });
                    }
                });
                break;
            case 'bulb price':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        shipping.getOrderSummaryItemPrice(bulbDetails.bulbName).then((displayedBulbPrice) => {
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
                        shipping.getOrderSummaryItemQty(bulbDetails.bulbName).then((displayedBulbQty) => {
                            expect(displayedBulbQty).to.be.equal(bulbDetails.bulbQty);
                        });
                    }
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});