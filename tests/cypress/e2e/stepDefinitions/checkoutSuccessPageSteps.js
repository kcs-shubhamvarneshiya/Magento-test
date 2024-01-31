const { Then } = require ('@badeball/cypress-cucumber-preprocessor');

import CheckoutSuccessPage from '../pageObjects/checkoutSuccessPage';
import { productDetails } from './productDescriptionPageSteps';

const checkout = new CheckoutSuccessPage();

Then('the product ship by date of checkout success page should be the same as displayed on the product description page', () => { 
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            //validate that the displayed product ship by date should be the same as on the PDP
            checkout.getProductShipByDate(productDetails.productName).then((displayedShipByDate) => {
                expect(displayedShipByDate).to.equal(productDetails.shipByDate);
            });
        } else {
            cy.log('User can not place an order on the production because of reCaptcha');
        }
    });
});