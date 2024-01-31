const { Given, When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import HeaderPage from '../pageObjects/headerPage';
import HomePage from '../pageObjects/homePage';
import ProductListPage from '../pageObjects/productListPage';
import ProductDescriptionPage from '../pageObjects/productDescriptionPage';
import ShippingPage from '../pageObjects/shippingPage';
import PaymentMethodsPage from '../pageObjects/paymentMethodsPage';
import CheckoutSuccessPage from '../pageObjects/checkoutSuccessPage';
import LoginPage from '../pageObjects/loginPage';
import CreateNewAccountPage from '../pageObjects/createNewAccountPage';

const header = new HeaderPage();
const home = new HomePage();
const productList = new ProductListPage();
const pdp = new ProductDescriptionPage();
const shipping = new ShippingPage();
const payment = new PaymentMethodsPage();
const checkout = new CheckoutSuccessPage();
const login = new LoginPage();
const signUp = new CreateNewAccountPage();

let isContinue = 'yes', skippingReason = ''; 

Given('user is on the home page', () => {
    //open home page
    home.openHomePageUrl();

    //validate home page title
    home.validateHomePageTitle();
});

Given('user is on the login page', () => {
    //open login page
    login.openLoginPageUrl();

    //validate login page title
    login.validateLoginPageTitle();
});

Given('user is on the create new account page', () => {
    //open sign up page
    signUp.openSignUpPageUrl();

    //validate sign up page title
    signUp.validateSignUpPageTitle('Create New Customer Account');
});

When('{string} user do login into the website', (user) => {
    //do login
    login.performLogin(user);

    //validate whether it gets login or not
    header.getUserIcon().should('be.visible');
});

When('the retail user creates a new account with the following details:', (dataTable) => {
    //enter the account details
    signUp.enterRetailAccountDetails(dataTable);

    //click on the create account button
    signUp.clickCreateAccountButton();

    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            
            //validate the success msg
            signUp.getThankYouForRegisteringMsg().should('be.visible');

            //validate whether it gets login or not
            header.getUserIcon().should('be.visible');
        }
    });
});

When('user search {string}', (searchText) => {
    //enter search text
    header.enterSearchText(searchText);
});

When('user select {string} search option', (option) => {
    //click on the search option
    header.clickSearchOption(option);

    //validate whether the page header contains search result text or not
    productList.getPageHeader().should('contain.text', option.toLowerCase());
});

When('user click on the {string} submenu of {string} mega menu', (submenu,megaMenu) => {
    //mouse over on the mega menu
    header.mouseoverMegaMenu(megaMenu);
    //click on the submenu
    header.clickSubmenu(megaMenu, submenu);

    //validate whether the page header contains submenu name or not
    productList.getPageHeader().should('contain.text', submenu);
});

When('user click on the {string} product from the products list', (product) => {
    //click on the product name
    productList.clickProductName(product);

    //validate whether the pdp page title contains product name or not
    pdp.getPDPTitle().should('contain', product); 
});

When('user enter the following product description page url:', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const pdpUrl = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                //open pdp url
                pdp.openProductDescriptionPageUrl(pdpUrl);
   
                //validate the product description page url
                pdp.getPDPUrl().should('contain', pdpUrl);
            }
        }
    });
});

When('user select the following product variants:', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) { 
            const country = dataTable.rawTable[i][0].toLowerCase();
            const variant = dataTable.rawTable[i][1];
            const value = dataTable.rawTable[i][2];

            if (testingCountry === country) {
                //select provided variants
                pdp.selectVariant(variant, value);
            }
        }
    });
});

When('user click on the add to cart button', () => {
    //click on the add to cart button
    pdp.clickAddToCart();

    //wait till the product gets added to the cart
    pdp.getProductAddedMsg().should('be.visible');
});

When('user click on the begin checkout button from the mini cart', () => {
    //click on the begin checkout button from the mini cart
    header.clickBeginCheckOut();

    //validate shipping page title
    shipping.validateShippingPageTitle();
});

When('user enter the following shipping details:', (dataTable) => {
    const validFields = ['Email', 'First Name', 'Last Name','Address Line 1', 'Country', 'City', 'State', 'Zip Code', 'Phone Number'];

    cy.getCountry().then((testingCountry) => {        
        //enter the shipping details
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const field = dataTable.rawTable[i][1].toLowerCase();
            const value = dataTable.rawTable[i][2];
  
            if (testingCountry === country) {
                switch (field) {
                    case 'email':
                        shipping.enterEmail(value);
                        break;
                    case 'first name':
                        shipping.enterFirstName(value);
                        break;
                    case 'last name':
                        shipping.enterLastName(value);
                        break;
                    case 'address line 1':
                        shipping.enterAddressLine1(value);
                        break;
                    case 'country':
                        shipping.selectCountry(value);
                        break;
                    case 'state':
                        if(testingCountry === 'us'){
                            shipping.selectState(value);
                        } else {
                            shipping.enterState(value);
                        }
                        break;
                    case 'city':
                        shipping.enterCity(value);
                        break;
                    case 'zip code':
                        shipping.enterZipCode(value);
                        break;
                    case 'phone number':
                        shipping.enterPhoneNumber(value);
                        break;
                    default:
                        throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
                }
            }
        }
    });
});

When('user select the following shipping method', (dataTable) => {
    const usShippingMethod = ['2 day', 'standard overnight', 'standard shipping', 'priority overnight'];
    const ukShippingMethod = ['standard shipping'];
    const euShippingMethod = ['shipping will be calculated after checkout and sent via email', 'percent'];
    isContinue = 'yes', skippingReason = '';

    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const shippingMethod = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                if (testingCountry === 'us' && usShippingMethod.includes(shippingMethod.toLowerCase())) {
                    //select shipping method
                    shipping.selectShippingMethod(shippingMethod);
                } else if (testingCountry === 'uk' && ukShippingMethod.includes(shippingMethod.toLowerCase())) {
                    //select shipping method
                    shipping.selectShippingMethod(shippingMethod);
                } else if (testingCountry === 'eu' && euShippingMethod.includes(shippingMethod.toLowerCase())) {
                    //select shipping method
                    shipping.selectShippingMethod(shippingMethod);
                } else {
                    isContinue = 'no';
                    skippingReason = shippingMethod + ' shipping method is not available for ' + testingCountry.toUpperCase() + ' country';
                    cy.log(skippingReason);
                }
            }
        }
    });
}); export { isContinue, skippingReason }

When('user click on the continue to payment button', () => {
    if (isContinue === 'yes') {
        //click on the continue to payment button
        shipping.clickContinueToPaymentButton();

        //validate payment page url
        payment.validatePaymentPageUrl();
    } else {
        cy.log(skippingReason);
    }
});

When('user select the following payment method', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const paymentMethod = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                if (testingCountry === 'us') {
                    //select payment method
                    payment.selectPaymentMethod(paymentMethod);
                } else if (testingCountry !== 'us' && (paymentMethod.toLowerCase() === 'credit/debit card' || paymentMethod.toLowerCase() === 'credit card')) {
                    //select payment method
                    payment.selectPaymentMethod(paymentMethod);
                } else {
                    cy.log(paymentMethod + ' payment method is not available for ' + testingCountry.toUpperCase() + ' country');
                }
            } 
        }
    });
});

When('{string} user enters the following credit card or po details:', (user, dataTable) => {
    const validFields = ['Name', 'Card Number', 'Expiration Month', 'Expiration Year', 'CVV', 'PO Number'];

    cy.getCountry().then((testingCountry) => {
        //enter the card and/or po details
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const field = dataTable.rawTable[i][1].toLowerCase();
            const value = dataTable.rawTable[i][2];

            if (testingCountry === country) { 
                switch (field) {
                    case 'name':
                        payment.enterCardName(value);
                        break;
                    case 'card number':
                        if (testingCountry === 'us') {
                            if (user.toLowerCase() !== 'wholesale'){
                                payment.enterCardNumber(value);
                            } else {
                                payment.enterIFrameCardNumber(value);
                            }
                        } else if (testingCountry === 'uk') {
                            payment.enterIFrameCardNumber(value);
                        } else if (testingCountry === 'eu') {
                            cy.log('Skipping this because of the reported BUG 10759');
                            //payment.enterFlexCardNumber(value);
                        }
                        break;
                    case 'expiration month':
                        payment.selectExpirationMonth(value);
                        break;
                    case 'expiration year':
                        payment.selectExpirationYear(value);
                        break;
                    case 'cvv':
                        if (testingCountry === 'us') {
                            if (user.toLowerCase() !== 'wholesale'){
                                payment.enterCVV(value);
                            } else {
                                payment.enterIFrameCVV(value);
                            }
                        } else if (testingCountry === 'uk') {
                            payment.enterIFrameCVV(value);
                        } else if (testingCountry === 'eu') {
                            cy.log('Skipping this because of the reported BUG 10759');
                            //payment.enterFlexCVV(value);
                        }
                        break;
                    case 'po number':
                        payment.enterPoNumber(value);
                        break;
                    default:
                        throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
                }
            }
        }
    }); 
});

When('user click on the place order button', () => {
    //click on the place order button
    payment.clickPlaceOrderButton();
});

When('user enter {string} affirm mobile number', (number) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //enter mobile number
            payment.enterAffirmMobile(number);
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });   
});

When('user click on the affirm continue button', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the continue button
            payment.clickAffirmContinueButton();
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });  
});

When('user enter {string} affirm mobile pin', (pin) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //enter mobile pin
            payment.enterAffirmMobilePin(pin);
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user select {string} affirm plan', (plan) => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //select affirm plan
            payment.clickAffirmPlan(plan);
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user click on the affirm plan continue button', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the continue button
            payment.clickAffirmPlanContinueButton();
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user click on the I agree checkbox of affirm auto-pay', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //check I agree checkbox
            payment.clickAffirmAutoPayAgreeCheckbox();
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('user click on the affirm auto-pay confirm button', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the confirm button
            payment.clickAffirmAutoPayConfirmButton();
        } else {
            cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

Then('order of product should get placed with the following success message:', (dataTable) => {
    const successMessage = dataTable.rawTable[1];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry !== 'eu') {
                    //validate the thank you msg
                    checkout.getThankYouText().should('have.text', successMessage.toString());
                } else {
                    cy.log('Skipping this because of the reported BUG 10759');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

When('new user enter the following product description page url:', (dataTable) => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {
                for (let i = 1; i < dataTable.rawTable.length; i++) {
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const pdpUrl = dataTable.rawTable[i][1];
        
                    if (testingCountry === country) {
                        //open pdp url
                        pdp.openProductDescriptionPageUrl(pdpUrl);
           
                        //validate the product description page url
                        pdp.getPDPUrl().should('contain', pdpUrl);
                    }
                }
            });
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user select the following product variants:', (dataTable) => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {
                for (let i = 1; i < dataTable.rawTable.length; i++) { 
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const variant = dataTable.rawTable[i][1];
                    const value = dataTable.rawTable[i][2];
        
                    if (testingCountry === country) {
                        //select provided variants
                        pdp.selectVariant(variant, value);
                    }
                }
            });
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user click on the add to cart button', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            //click on the add to cart button
            pdp.clickAddToCart();

            //wait till the product gets added to the cart
            pdp.getProductAddedMsg().should('be.visible');
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user click on the begin checkout button from the mini cart', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            //click on the begin checkout button from the mini cart
            header.clickBeginCheckOut();

            //validate shipping page title
            shipping.validateShippingPageTitle();
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user enter the following shipping details:', (dataTable) => {
    const validFields = ['Email', 'First Name', 'Last Name','Address Line 1', 'Country', 'City', 'State', 'Zip Code', 'Phone Number'];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {        
                //enter the shipping details
                for (let i = 1; i < dataTable.rawTable.length; i++) {
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const field = dataTable.rawTable[i][1].toLowerCase();
                    const value = dataTable.rawTable[i][2];
          
                    if (testingCountry === country) {
                        switch (field) {
                            case 'email':
                                shipping.enterEmail(value);
                                break;
                            case 'first name':
                                shipping.enterFirstName(value);
                                break;
                            case 'last name':
                                shipping.enterLastName(value);
                                break;
                            case 'address line 1':
                                shipping.enterAddressLine1(value);
                                break;
                            case 'country':
                                shipping.selectCountry(value);
                                break;
                            case 'state':
                                if(testingCountry === 'uk'){
                                    shipping.enterState(value);
                                } else {
                                    shipping.selectState(value);
                                }
                                break;
                            case 'city':
                                shipping.enterCity(value);
                                break;
                            case 'zip code':
                                shipping.enterZipCode(value);
                                break;
                            case 'phone number':
                                shipping.enterPhoneNumber(value);
                                break;
                            default:
                                throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
                        }
                    }
                }
            });
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user select the following shipping method', (dataTable) => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {
                for (let i = 1; i < dataTable.rawTable.length; i++) {
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const shippingMethod = dataTable.rawTable[i][1];
        
                    if (testingCountry === country) {
                        //select shipping method
                        shipping.selectShippingMethod(shippingMethod);
                    }
                }
            });
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user click on the continue to payment button', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            //click on the continue to payment button
            shipping.clickContinueToPaymentButton();

            //validate payment page url
            payment.validatePaymentPageUrl();
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new user select the following payment method', (dataTable) => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {
                for (let i = 1; i < dataTable.rawTable.length; i++) {
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const paymentMethod = dataTable.rawTable[i][1];
        
                    if (testingCountry === country) {
                        //select payment method
                        payment.selectPaymentMethod(paymentMethod);
                    } 
                }
            });
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('new retail user enters the following credit card or po details:', (dataTable) => {
    const validFields = ['Name', 'Card Number', 'Expiration Month', 'Expiration Year', 'CVV', 'PO Number'];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            cy.getCountry().then((testingCountry) => {
                //enter the card and/or po details
                for (let i = 1; i < dataTable.rawTable.length; i++) {
                    const country = dataTable.rawTable[i][0].toLowerCase();
                    const field = dataTable.rawTable[i][1].toLowerCase();
                    const value = dataTable.rawTable[i][2];
        
                    if (testingCountry === country) { 
                        switch (field) {
                            case 'name':
                                payment.enterCardName(value);
                                break;
                            case 'card number':
                                if (testingCountry === 'us') {
                                    payment.enterCardNumber(value);
                                } else if (testingCountry === 'uk') {
                                    payment.enterIFrameCardNumber(value);
                                } else if (testingCountry === 'eu') {
                                    cy.log('Skipping this because of the reported BUG 10759');
                                    //payment.enterFlexCardNumber(value);
                                }
                                break;
                            case 'expiration month':
                                payment.selectExpirationMonth(value);
                                break;
                            case 'expiration year':
                                payment.selectExpirationYear(value);
                                break;
                            case 'cvv':
                                if (testingCountry === 'us') {
                                    payment.enterCVV(value);
                                } else if (testingCountry === 'uk') {
                                    payment.enterIFrameCVV(value);
                                } else if (testingCountry === 'eu') {
                                    cy.log('Skipping this because of the reported BUG 10759');
                                    //payment.enterFlexCVV(value);
                                }
                                break;
                            case 'po number':
                                payment.enterPoNumber(value);
                                break;
                            default:
                                throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
                        }
                    }
                }
            }); 
        } else {
            cy.log('Skipping this step because of reCaptcha on the production');
        }
    });
});

When('wholesale user click on the add to cart button', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the add to cart button
            pdp.clickAddToCart();

            //wait till the product gets added to the cart
            pdp.getProductAddedMsg().should('be.visible');
        } else {
            cy.log('Add to cart button is not available for wholesale user and ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('wholesale user click on the begin checkout button from the mini cart', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the begin checkout button from the mini cart
            header.clickBeginCheckOut();

            //validate shipping page title
            shipping.validateShippingPageTitle();
        } else {
            cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('wholesale user select the following shipping method', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const shippingMethod = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                if (testingCountry === 'us') {
                    //select shipping method
                    shipping.selectShippingMethod(shippingMethod);
                } else {
                    cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
                }  
            }
        }
    });
});

When('wholesale user click on the continue to payment button', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the continue to payment button
            shipping.clickContinueToPaymentButton();

            //validate payment page url
            payment.validatePaymentPageUrl();
        } else {
            cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
        }
    });
});

When('wholesale user select the following payment method', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const paymentMethod = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                if (testingCountry === 'us') {
                    //select payment method
                    payment.selectPaymentMethod(paymentMethod);
                } else {
                    cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
                }   
            } 
        }
    });
});

When('wholesale user enters the following credit card or po details:', (dataTable) => {
    const validFields = ['Name', 'Card Number', 'Expiration Month', 'Expiration Year', 'CVV', 'PO Number'];

    cy.getCountry().then((testingCountry) => {
        //enter the card and/or po details
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const field = dataTable.rawTable[i][1].toLowerCase();
            const value = dataTable.rawTable[i][2];

            if (testingCountry === country) { 
                if (testingCountry === 'us') { 
                    switch (field) {
                        case 'name':
                            payment.enterCardName(value);
                            break;
                        case 'card number':
                            payment.enterIFrameCardNumber(value);
                            break;
                        case 'expiration month':
                            payment.selectExpirationMonth(value);
                            break;
                        case 'expiration year':
                            payment.selectExpirationYear(value);
                            break;
                        case 'cvv':
                            payment.enterIFrameCVV(value);
                            break;
                        case 'po number':
                            payment.enterPoNumber(value);
                            break;
                        default:
                            throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
                    }
                } else {
                    cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
                }  
            }
        }
    }); 
});

When('wholesale user click on the place order button', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //click on the place order button
                    payment.clickPlaceOrderButton();
                } else {
                    cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

Then('order of product for wholesale user should get placed with the following success message:', (dataTable) => {
    const successMessage = dataTable.rawTable[1];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //validate the thank you msg
                    checkout.getThankYouText().should('have.text', successMessage.toString());
                } else {
                    cy.log('The wholesale user can not place an order in ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

When('user click on the place order button for affirm', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //click on the place order button
                    payment.clickPlaceOrderButton();
                } else {
                    cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

Then('order of product with affirm payment method should get placed with the following success message:', (dataTable) => {
    const successMessage = dataTable.rawTable[1];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //validate the thank you msg
                    checkout.getThankYouText().should('have.text', successMessage.toString());
                } else {
                    cy.log('Affirm payment method is not available for ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

When('user click on the place order button for net 30', () => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //click on the place order button
                    payment.clickPlaceOrderButton();
                } else {
                    cy.log('Net 30 payment method is not available for ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

Then('order of product with net 30 payment method should get placed with the following success message:', (dataTable) => {
    const successMessage = dataTable.rawTable[1];

    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') {
                if (testingCountry === 'us') {
                    //validate the thank you msg
                    checkout.getThankYouText().should('have.text', successMessage.toString());
                } else {
                    cy.log('Net 30 payment method is not available for ' + testingCountry.toUpperCase() + ' country');
                }
            } else {
                cy.log('User can not place an order on the production');
            }
        });
    });
});

Then('the following validation message should be displayed on the payment page', (dataTable) => {
    cy.getTestingEnvironment().then((testingEnvironment) => {
        cy.getCountry().then((testingCountry) => {
            if (testingEnvironment.toLowerCase() !== 'production') { 
                dataTable.hashes().forEach((row) => {
                    const country = row.country.toLowerCase();
                    const expectedMessage = row.message;
    
                    if (testingCountry === country) {
                        //validate the validation msg
                        payment.getFieldErrorMsg().should('have.text', expectedMessage);

                        //validate the payment page url
                        payment.validatePaymentPageUrl();
                    } 
                });
            } else {
                cy.log('User can not place an order on the production because of reCaptcha');
            }
        });
    });
});