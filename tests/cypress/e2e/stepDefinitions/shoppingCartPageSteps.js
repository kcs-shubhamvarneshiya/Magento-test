const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import ShoppingCartPage from '../pageObjects/shoppingCartPage';
import HeaderPage from '../pageObjects/headerPage';
import { productDetails, bulbDetails } from './productDescriptionPageSteps';
import ProductDescriptionPage from '../pageObjects/productDescriptionPage';

const shoppingCart = new ShoppingCartPage();
const header = new HeaderPage();
const productDescription = new ProductDescriptionPage();

let orderDetails;

When('user click on the mini cart icon', () => {
    //click on the mini cart
    header.clickMiniCartIcon();
});

Then('the shopping cart page should get opened which should contain the following url', (dataTable) => {
    const expectedCartUrl = dataTable.rawTable[1].toString(); 

    //validate the shopping cart url
    shoppingCart.getShoppingCartPageUrl().then((currentUrl) => {
        expect(currentUrl.endsWith(expectedCartUrl.toLowerCase()), 'Navigate to url - ' + currentUrl).to.be.true;
    }); 
});

When('user navigate to shopping cart page', () => {
    //validate the shopping cart page title
    shoppingCart.validateShoppingCartPageTitle();
});

Then('user should be able to see below message for clear cart', (dataTable) => {
    const expectedMessage = dataTable.rawTable[1].toString(); 

    //validate the clear cart success message
    shoppingCart.getCartEmptyMessage().then((actualMessage) => {
        expect(actualMessage.text().toLowerCase()).to.contain(expectedMessage.toLowerCase());  
    }); 
});

When('user click on the clear cart button from the shopping cart page', () => {
    //click on the clear cart button       
    shoppingCart.clickOnClearCartButton();  
});

When('user click on the {string} button from the clear shopping cart pop up', (buttonName) => {
    //click on the ok, cancel or x icon button 
    if (buttonName.toLowerCase() === 'ok') {
        shoppingCart.clickOnClearCartPopupOkButton();   
    } else if (buttonName.toLowerCase() === 'cancel') {
        shoppingCart.clickOnClearCartPopupCancelButton();
    } else if (buttonName.toLowerCase() === 'x icon') {
        shoppingCart.clickOnClearCartPopupXIconButton();
    } else {
        throw new Error(`The provided '${buttonName}' button name is not valid. Valid name is Ok, Cancel or X Icon.`);
    }
});

When('user click on the {string} link of product', (linkName) => {
    //click on the remove/edit/move to project/add to quote link
    if (linkName.toLowerCase() === 'remove') {
        shoppingCart.clickRemoveLinkOfItem(productDetails.productName);
    } else if (linkName.toLowerCase() === 'edit') {
        shoppingCart.clickEditLinkOfItem(productDetails.productName);
    } else if (linkName.toLowerCase() === 'move to project') {
        shoppingCart.clickMoveToProjectLinkOfItem();
    } else if (linkName.toLowerCase() === 'add to quote') {
        shoppingCart.clickAddToQuoteLinkOfItem(productDetails.productName);
    } else {
        throw new Error(`The provided '${linkName}' link name is not valid. Valid name is Remove, Edit, Add to Quote or Move to Project.`);
    }
});

Then('the product should not be displayed on the shopping cart page', () => {
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') { 
            //validate the removed product name should not be displayed
            shoppingCart.getItemNameList().then((displayedItemNameList) => {
                expect(displayedItemNameList).not.to.be.include(productDetails.productName);
            });
        } else {
            //validate the clear cart success message
            shoppingCart.getCartEmptyMessage().should('be.visible');
        }
    });
});

When('user click on the product image from the shopping cart page', () => {
    // click on product image     
    shoppingCart.clickOnItemImage(productDetails.productName);   
});

Then('user should be redirected to the product description page which has the following page title', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedPageTitle = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                // validate whether the user redirect to product description page or not
                productDescription.getPDPTitle().then((currentPageTitle) => {
                    expect(currentPageTitle.replace('|', '')).to.be.equal(expectedPageTitle);
                });
            }
        }
    });
});

When('user click on the update cart button', () => {
    //click on the update cart button from the pdp 
    productDescription.clickUpdateCart();   
});

Then('user should be able to see the below success message on shopping cart page', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) { 
            const country = dataTable.rawTable[i][0].toLowerCase();
            const successMessage = dataTable.rawTable[i][1].toString(); 

            if (testingCountry === country) {
                //validate the displayed success message
                shoppingCart.getSuccessMessage().then((displayedMessage) => {
                    expect(displayedMessage.toLowerCase()).to.equal(successMessage.toLowerCase());  
                }); 
            }
        }
    });
});

When('user click on the product name from the shopping cart page', () => {
    // click on product name     
    shoppingCart.clickOnItemName(productDetails.productName);   
});

When('user update {int} quantity of product', (qty) => {
    //enter the product qty  
    shoppingCart.enterItemQty(productDetails.productName, qty);   
});

Then('the subtotal of product should get updated as per the entered {int} product quantity', (qty) => { 
    //validate that the displayed item subtotal should be the multiplication of item price and updated qty
    shoppingCart.getItemSubtotalAsNumber(productDetails.productName).then((displayedItemSubtotal) => {

        cy.getCountry().then((testingCountry) => {    
            if (testingCountry === 'us') {
                cy.convertPriceToNumber(productDetails.productPrice, '$').then((itemPrice) => {
                    expect(parseFloat(displayedItemSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * qty).toFixed(2));
                });
            } else if (testingCountry === 'uk') {
                cy.convertPriceToNumber(productDetails.productPrice, '£').then((itemPrice) => {
                    cy.log('Skipping this validation because of the reported BUG 10694');
                    //expect(parseFloat(displayedItemSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * qty).toFixed(2));
                });
            } else if (testingCountry === 'eu') {
                cy.convertPriceToNumber(productDetails.productPrice, '€').then((itemPrice) => {
                    cy.log('Skipping this validation because of the reported BUG 10694');
                    //expect(parseFloat(displayedItemSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * qty).toFixed(2));
                });
            }
        });
    });
});

Then('the order subtotal displayed in the order summary should be a sum of all products subtotal', () => { 
    //validate that the displayed order subtotal should be a sum of all products subtotal
    shoppingCart.getSumOfAllItemSubtotal().then((allItemSubtotal) => {
        shoppingCart.getOrderSubtotalAsNumber().then((displayedOrderedSubtotal) => {
            cy.getCountry().then((testingCountry) => {
                if (testingCountry === 'us') {
                    expect(parseFloat(displayedOrderedSubtotal).toFixed(2)).to.be.equal(parseFloat(allItemSubtotal).toFixed(2));
                } else {
                    shoppingCart.getVATChargeAsNumber().then((displayedVATCharge) => {
                        expect(parseFloat(displayedOrderedSubtotal).toFixed(2)).to.be.equal(parseFloat(allItemSubtotal - displayedVATCharge).toFixed(2));
                    });
                }
            });
        });
    });
});

Then('the merchandise total displayed in the order summary should be a sum of order subtotal, shipping charges and tax', () => { 
    //validate that the displayed merchandise total should be a sum of order subtotal, shipping charges and tax
    shoppingCart.getOrderSubtotalAsNumber().then((displayedOrderedSubtotal) => {
        shoppingCart.getOrderTaxAsNumber().then((displayedOrderedTax) => {
            shoppingCart.getMerchandiseTotalAsNumber().then((displayedMerchandiseTotal) => {
                shoppingCart.getShippingChargeAsNumber().then((displayedShippingCharge) => {
                    expect(parseFloat(displayedMerchandiseTotal).toFixed(2)).to.be.equal(parseFloat(displayedOrderedSubtotal + displayedOrderedTax + displayedShippingCharge).toFixed(2));
                });
            });
        });
    });
});

When('user click on the add item button from the requisition list popup', () => {
    //click on add item button
    shoppingCart.clickAddItemButtonOfRequisitionListPopup();   
});

When('user select {string} from add to quote popover', (quoteName) => {
    // select quote 
    shoppingCart.selectQuoteFromAddToQuote(quoteName);
});

Then('the subtotal of product should be the multiplication of product price and qty', () => { 
    //validate that the displayed product subtotal should be the multiplication of price and qty
    shoppingCart.getItemSubtotalAsNumber(productDetails.productName).then((displayedProductSubtotal) => {

        cy.getCountry().then((testingCountry) => {    
            if (testingCountry === 'us') {
                cy.convertPriceToNumber(productDetails.productPrice, '$').then((itemPrice) => {
                    expect(parseFloat(displayedProductSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * productDetails.productQty).toFixed(2));
                });
            } else if (testingCountry === 'uk') {
                cy.convertPriceToNumber(productDetails.productPrice, '£').then((itemPrice) => {
                    expect(parseFloat(displayedProductSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * productDetails.productQty).toFixed(2));
                });
            } else if (testingCountry === 'eu') {
                cy.convertPriceToNumber(productDetails.productPrice, '€').then((itemPrice) => {
                    expect(parseFloat(displayedProductSubtotal).toFixed(2)).to.be.equal(parseFloat(itemPrice * productDetails.productQty).toFixed(2));
                });
            }
        });
    });
});

Then('the subtotal of bulb should be the multiplication of bulb price and qty', () => { 
    //validate that the displayed bulb subtotal should be the multiplication of price and qty
    cy.getCountry().then((testingCountry) => {    
        if (testingCountry === 'us') {
            shoppingCart.getItemSubtotalAsNumber(bulbDetails.bulbName).then((displayedBulbSubtotal) => {
                cy.bulbPricePerUnit(bulbDetails.bulbPrice, bulbDetails.bulbQty).then((bulbPricePerUnit) => {
                    expect(parseFloat(displayedBulbSubtotal).toFixed(2)).to.be.equal(parseFloat(bulbPricePerUnit * bulbDetails.bulbQty).toFixed(2));
                });
            });
        } else {
            cy.log('Bulb option is not available for the ' + testingCountry.toUpperCase());
        }
    });
});

Then('the product ship by date of cart page should be the same as displayed on the product description page', () => { 
    //validate that the displayed product ship by date should be the same as on the PDP
    shoppingCart.getItemShipByDate(productDetails.productName).then((displayedShipByDate) => {
        expect(displayedShipByDate).to.equal(productDetails.shipByDate);
    });
});

When('user click on the begin checkout button from the shopping cart page', () => { 
    //click on the begin checkout button
    shoppingCart.clickBeginCheckoutButton();   
});

When('user collect the following details from the shopping cart page', (dataTable) => {
    const validLabel = ['Cart Subtotal', 'Shipping Charges', 'Tax/Vat', 'Merchandise Total', 'Vat'];
    orderDetails = {};

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //collect the details displayed on the shopping cart page
        switch (label) {
            case 'cart subtotal':
                shoppingCart.getOrderSubtotalAmt().then((displayedOrderSubtotal) => {
                    orderDetails.cartSubtotal = displayedOrderSubtotal;
                });
                break;
            case 'shipping charges':
                shoppingCart.getShippingCharge().then((displayedShippingCharge) => {
                    orderDetails.shippingCharge = displayedShippingCharge;
                });
                break;
            case 'tax/vat':
                shoppingCart.getOrderTax().then((displayedOrderTax) => {
                    orderDetails.orderTax = displayedOrderTax;
                });
                break;
            case 'merchandise total':
                shoppingCart.getMerchandiseTotal().then((displayedMerchandiseTotal) => {
                    orderDetails.merchandiseTotal = displayedMerchandiseTotal;
                });
                break;
            case 'vat':
                cy.getCountry().then((testingCountry) => {    
                    if (testingCountry !== 'us') {
                        shoppingCart.getOrderTax().then((displayedOrderTax) => {
                            orderDetails.orderTax = displayedOrderTax;
                        });
                    }
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
}); export { orderDetails }