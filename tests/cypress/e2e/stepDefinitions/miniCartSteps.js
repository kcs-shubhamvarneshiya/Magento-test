const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import HeaderPage from '../pageObjects/headerPage';
import ShippingPage from '../pageObjects/shippingPage';
import ProductDescriptionPage from '../pageObjects/productDescriptionPage';
import { productDetails, bulbDetails } from './productDescriptionPageSteps';

const header = new HeaderPage();
const shipping = new ShippingPage();
const pdp = new ProductDescriptionPage();

When('user click on the view cart button from the mini cart', () => {
    //click on the view cart button
    header.clickMiniCartViewCartButton();
});

When('user mouse hover on the mini cart icon', () => {
    //mouseover on mini cart icon
    header.mouseoverMiniCartIcon();
});

When('user click on the product name from the mini cart', () => {
    //click on the product name
    header.clickMiniCartItemName(productDetails.productName);
});

Then('user should be able to see below message on the mini cart', (dataTable) => {
    const expectedMessage = dataTable.rawTable[1].toString();
 
    //validate the no items in cart message
    header.getNoItemsInMiniCartMessage().then((displayedMessage) => {
        expect(displayedMessage.toLowerCase()).to.equal(expectedMessage.toLowerCase());  
    }); 
});

When('user click on the product image from the mini cart', () => {
    //click on the product image
    header.clickMiniCartItemImg(productDetails.productName);
});

Then('the shipping page should get opened which should contain the following url', (dataTable) => {
    const expectedPageUrl = dataTable.rawTable[1].toString(); 

    //validate the shipping page url
    shipping.getShippingPageUrl().then((currentUrl) => {
        expect(currentUrl.endsWith(expectedPageUrl.toLowerCase()), 'Navigate to url - ' + currentUrl).to.be.true;
    }); 
});

Then('the subtotal should be the sum of all product price * qty', () => {
    let expectedSubtotal;

    //click on the see details link to open the mini cart popup
    header.clickMiniCartItemSeeDetailsLink(productDetails.productName);

    //get the subtotal displayed on the mini cart and also get the price and qty of all added products/items
    header.getMiniCartSubtotalAsNumber().then((displayedSubtotal) => {

        cy.getCountry().then((testingCountry) => {    
            if (testingCountry === 'us') {
                cy.convertPriceToNumber(productDetails.productPrice, '$').then((productPriceAsNumber) => {
                    cy.convertStringToNumber(productDetails.productQty).then((productQtyAsNumber) => {
                        cy.bulbPricePerUnit(bulbDetails.bulbPrice, bulbDetails.bulbQty).then((bulbPricePerUnit) => {
                            cy.convertStringToNumber(bulbDetails.bulbQty).then((bulbQtyAsNumber) => {
                                expectedSubtotal = ((parseFloat(productPriceAsNumber).toFixed(2)) * productQtyAsNumber) + ((parseFloat(bulbPricePerUnit).toFixed(2)) * bulbQtyAsNumber);

                                //validate the displayed subtotal
                                expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                            });
                        });
                    });
                });
            } else if (testingCountry === 'uk') {
                cy.convertPriceToNumber(productDetails.productPrice, '£').then((productPriceAsNumber) => {
                    cy.convertStringToNumber(productDetails.productQty).then((productQtyAsNumber) => {
                        expectedSubtotal = (parseFloat(productPriceAsNumber).toFixed(2)) * productQtyAsNumber;

                        cy.log('Skipping this validation because of the reported BUG 10670');
                        //validate the displayed subtotal
                        //expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                    });
                });   
            } else if (testingCountry === 'eu') {
                cy.convertPriceToNumber(productDetails.productPrice, '€').then((productPriceAsNumber) => {
                    cy.convertStringToNumber(productDetails.productQty).then((productQtyAsNumber) => {
                        expectedSubtotal = (parseFloat(productPriceAsNumber).toFixed(2)) * productQtyAsNumber;

                        cy.log('Skipping this validation because of the reported BUG 10670');
                        //validate the displayed subtotal
                        //expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                    });
                });   
            }
        });
    });
});

When('user click on the {string} link of product from the mini cart', (linkName) => {
    //click on the remove or edit link
    if (linkName.toLowerCase() === 'remove') {
        header.clickMiniCartItemRemoveLink(productDetails.productName);
    } else if (linkName.toLowerCase() === 'edit') {
        header.clickMiniCartItemEditLink(productDetails.productName);
    } else {
        throw new Error(`The provided '${linkName}' link name is not valid. Valid name is Remove or Edit.`);
    }
});

Then('the removed product should not be displayed on the mini cart', () => {
    //validate the removed product name should not be displayed
    header.getMiniCartRemovedItemName(productDetails.productName).should('not.exist');
});

Then('the update cart button should be displayed on the product description page', () => {
    //validate the update cart button should be displayed
    pdp.getUpdateCartButton().should('be.visible');
});

Then('the following details should be the same as entered before on the product description page', (dataTable) => {
    const validLabel = ['Product Qty', 'Finish'];
   
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the product description page
        switch (label) {
            case 'product qty':
                pdp.getProductQty().then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                pdp.waitForProductImageDisplayAsSelected();
                pdp.getSelectedDropdownOption(row.label).then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

When('user update the {string} product qty from the mini cart', (qty) => {
    //click on the see details link to open the mini cart popup
    header.clickMiniCartItemSeeDetailsLink(productDetails.productName);

    //enter or update the product quantity
    header.enterMiniCartItemQty(productDetails.productName, qty);
});

When('user click on the update button from the mini cart', () => {
    //click on the see details link to open the mini cart popup
    header.clickMiniCartItemSeeDetailsLink(productDetails.productName);

    //click on the update product quantity button from the mini cart
    header.clickMiniCartUpdateQtyButton(productDetails.productName);
});

When('user reload the current page', () => {
    //reload the current page
    header.reloadCurrentPage();
});


Then('the item count should be the sum of all product qty', () => {
    let expectedItemCount;

    //get the item quantity displayed on the mini cart and also get the qty of all added products
    header.getMiniCartItemCountAsNumber().then((displayedItemCount) => {

        cy.getCountry().then((testingCountry) => {    
            if (testingCountry === 'us') {
                cy.convertStringToNumber(productDetails.productQty).then((productQtyAsNumber) => {
                    cy.convertStringToNumber(bulbDetails.bulbQty).then((bulbQtyAsNumber) => {
                        expectedItemCount = productQtyAsNumber + bulbQtyAsNumber;

                        //validate the displayed item count
                        expect(displayedItemCount).to.be.equal(expectedItemCount);
                    });
                });
            } else {
                cy.convertStringToNumber(productDetails.productQty).then((productQtyAsNumber) => {
                    expectedItemCount = productQtyAsNumber;

                    //validate the displayed item count
                    expect(displayedItemCount).to.be.equal(expectedItemCount);
                });
            }                
        });
    });
});

Then('the subtotal and cart item count should be updated as per the updated {int} product qty', (expectedProductQty) => {
    let expectedSubtotal;

    //click on the see details link to open the mini cart popup
    header.clickMiniCartItemSeeDetailsLink(productDetails.productName);

    //validate the displayed item count
    header.getMiniCartItemCountAsNumber().then((displayedItemCount) => {
        expect(displayedItemCount).to.be.equal(expectedProductQty);
    });

    //get the subtotal displayed on the mini cart and also get the price and qty of added product
    header.getMiniCartSubtotalAsNumber().then((displayedSubtotal) => {
        cy.getCountry().then((testingCountry) => {    
            if (testingCountry === 'us') {
                cy.convertPriceToNumber(productDetails.productPrice, '$').then((productPriceAsNumber) => {
                    expectedSubtotal = (parseFloat(productPriceAsNumber).toFixed(2)) * expectedProductQty;

                    //validate the displayed subtotal
                    expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                });
            } else if (testingCountry === 'uk') {
                cy.convertPriceToNumber(productDetails.productPrice, '£').then((productPriceAsNumber) => {
                    expectedSubtotal = (parseFloat(productPriceAsNumber).toFixed(2)) * expectedProductQty;

                    cy.log('Skipping this validation because of the reported BUG 10670');
                    //validate the displayed subtotal
                    //expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                });   
            } else if (testingCountry === 'eu') {
                cy.convertPriceToNumber(productDetails.productPrice, '€').then((productPriceAsNumber) => {
                    expectedSubtotal = (parseFloat(productPriceAsNumber).toFixed(2)) * expectedProductQty;

                    cy.log('Skipping this validation because of the reported BUG 10670');
                    //validate the displayed subtotal
                    //expect(parseFloat(displayedSubtotal).toFixed(2)).to.be.equal(parseFloat(expectedSubtotal).toFixed(2));
                }); 
            }
        });
    });
});