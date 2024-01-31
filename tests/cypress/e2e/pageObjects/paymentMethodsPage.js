const paymentMethod = '.payment-method-title > .label > span';
const cardName = 'input[name="payment[name_on_card]"]';
const iframeCardNumber = 'input#number';
const iframeCVV = 'input#securityCode';
const cardNumber = 'input[name="payment[cc_number]"]';
const expirationMonth = 'select[name="payment[cc_exp_month]"]';
const expirationYear = 'select[name="payment[cc_exp_year]"]';
const cvv = 'input[name="payment[cc_cid]"]';
const placeOrder = '._active .action.primary.checkout';
const placeOrderLabel = 'Place Order';
const paymentIFrame = 'iframe[title="secure payment field"]';
const affirmIFrame = '.affirm-sandbox-iframe#checkout-application';
const iFrameBody = '0.contentDocument.body';
const poNumber = '._active .required-entry.validate-custom-po-number';
const affirmMobile = 'input[data-testid="phone-number-field"]';
const affirmContinueButton = 'button[data-testid="submit-button"]';
const affirmMobilePin = 'input[data-testid="phone-pin-field"]';
const affirmPlanRoot = 'span';
const affirmPlanParent = 'div[class="proXxAyyoTg pro1jhMLxqa pro7WtJNbc4 pro1ZtlPpQQ pro1VoFYgWa"]';
const affirmPlan = 'div[data-testid="indicator"]';
const affirmPlanContinueButton = 'button[data-testid="continue-with-selected-term-button"]';
const affirmPlanContinueButtonLabel = 'Choose this plan';
const affirmAutoPayAgreeCheckbox = 'div[data-testid="disclosure-checkbox-indicator"]';
const affirmAutoPayConfirmButton = 'button[data-testid="submit-button"]';
const affirmAutoPayConfirmButtonLabel = 'Confirm';
const paymentPageUrl = 'checkout/#payment';
const shippingMethodShippingSection = 'div[class="shipping-information-content"] > .value';
const shippingMethodOrderSummarySection = 'tr.totals.shipping.excl > th > span.value';
const shippingDateShippingSection = 'div[class="shipping-information-content"] > strong > .value';
const flexCardNumber = 'input[name="microform"]';
const flexCVV = 'input[name="microform-cvn"]';
const fieldErrorMsg = 'div.mage-error';


class PaymentMethodsPage {

    selectPaymentMethod (value) {
        cy.get(paymentMethod).contains(value, {matchCase: false}).scrollIntoView().click();
    } 

    getPageUrl () {
        return cy.url();
    }   

    validatePaymentPageUrl () {
        this.getPageUrl().should('contain', paymentPageUrl);
    }

    enterCardName (value) {
        cy.get(cardName).should('be.visible').clear().type(value);
    }

    enterCardNumber (value) {
        cy.get(cardNumber).should('be.visible').clear().type(value);
    }

    selectExpirationMonth (value) {
        cy.get(expirationMonth).should('be.visible').select(value);
    }

    selectExpirationYear (value) {
        cy.get(expirationYear).should('be.visible').select(value);
    }

    enterCVV (value) {
        cy.get(cvv).should('be.visible').clear().type(value);
    }

    clickPlaceOrderButton () {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            cy.getCountry().then((testingCountry) => {
                if (testingEnvironment.toLowerCase() !== 'production') {
                    if (testingCountry !== 'eu') {
                        cy.get(placeOrder).contains(placeOrderLabel, {matchCase: false}).should('be.exist').click();
                    } else {
                        cy.log('Skipping this because of the reported BUG 10759');
                    }
                } else {
                    cy.log('User can not place an order on the production');
                }
            });
        });
    }

    getPaymentIFrame (index) {
        return cy.get(paymentIFrame).eq(index).its(iFrameBody).should('not.be.empty');
    }

    enterIFrameCardNumber (value) {
        this.getPaymentIFrame(0).then((iFrame) => {
            cy.wrap(iFrame).find(iframeCardNumber).clear().type(value);
        });
    }

    enterIFrameCVV (value) {
        this.getPaymentIFrame(1).then((iFrame) => {
            cy.wrap(iFrame).find(iframeCVV).clear().type(value);
        });
    }

    enterPoNumber (value) {
        cy.get(poNumber).type(value)
    }

    getAffirmIFrame () {
        return cy.get(affirmIFrame).its(iFrameBody).should('not.be.empty');
    }

    enterAffirmMobile (value) {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmMobile).should('be.visible').should('not.be.disabled');
                    cy.wrap(iFrame).find(affirmMobile).should('not.be.disabled').type(value);
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    enterAffirmMobilePin (value) {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmMobilePin).should('be.visible').type(value);
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    clickAffirmPlan (value) {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmPlanRoot).contains(value, {matchCase: false}).parents(affirmPlanParent)
                    .find(affirmPlan).should('be.visible').click();
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    clickAffirmPlanContinueButton () {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmPlanContinueButton).contains(affirmPlanContinueButtonLabel, {matchCase: false})
                    .should('exist').scrollIntoView().click({force: true});
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });  
    }

    clickAffirmContinueButton () {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmContinueButton).should('be.visible').click();
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    clickAffirmAutoPayAgreeCheckbox () {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmAutoPayAgreeCheckbox).should('exist').scrollIntoView().click({force: true});
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    clickAffirmAutoPayConfirmButton () {
        cy.getTestingEnvironment().then((testingEnvironment) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                this.getAffirmIFrame().then((iFrame) => {
                    cy.wrap(iFrame).find(affirmAutoPayConfirmButton).contains(affirmAutoPayConfirmButtonLabel, {matchCase: false})
                    .should('exist').scrollIntoView().click({force: true});
                });
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    }

    getShippingMethodOfShippingSection () {
        return cy.get(shippingMethodShippingSection);
    }

    getShippingMethodOfOrderSummarySection () {
        return cy.get(shippingMethodOrderSummarySection);
    }

    getShippingDateOfShippingSection () {
        return cy.get(shippingDateShippingSection);
    }

    enterFlexCardNumber (value) {
        cy.get(flexCardNumber).type(value);
    }

    enterFlexCVV (value) {
        cy.get(flexCVV).type(value);
    }

    getFieldErrorMsg () {
        return cy.get(fieldErrorMsg);
    }

} export default PaymentMethodsPage