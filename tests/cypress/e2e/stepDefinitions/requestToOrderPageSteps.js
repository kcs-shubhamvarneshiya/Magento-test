const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import { productDetails } from './productDescriptionPageSteps';
import RequestToOrderPage from '../pageObjects/requestToOrderPage';

const requestToOrderPage = new RequestToOrderPage();

let expectedNumberOfItems;

Then('user should be able to see {int} as the default pagination on the request to order page', (expectedPaginationOption) => {
    //validate the default selected option of pagination
    requestToOrderPage.getSelectedPaginationDropdownOption().then((actualSelectedPaginationOption) => {
        expect(actualSelectedPaginationOption).to.eq(expectedPaginationOption.toString());
    });
});

Then('the item count should be the same as the added request to order', () => {
    //load all items
    requestToOrderPage.selectPaginationDropdownOption("50");

    //validate the item count
    requestToOrderPage.getItemCount().then((actualItemCount) => {
        requestToOrderPage.getRequestToOrderList().then((requestToOrderList) => {
            const expectedItemCount = requestToOrderList.length;
            expect(Number(actualItemCount)).to.eq(Number(expectedItemCount));
        });
    });
});

When('user update {int} quantity of product from the request page', (quantityNumber) => {
    //enter product quantity
    requestToOrderPage.enterProductQuantity(productDetails.productName.replace('"', ""), quantityNumber);

    //click on the update button
    requestToOrderPage.clickOnUpdateButton();
});

When('user click on the view link of below request order number', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const number = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {
                //click on the view link of request order number
                requestToOrderPage.clickOnViewLink(number);
                break;
            }
        }
    });
});

Then('the subtotal of product should get updated as per {int} product quantity', (quantity) => {
    //validate that the displayed item subtotal should be the multiplication of item price and updated qty
    requestToOrderPage.getItemPrice(productDetails.productName.replace('"', "")).then((displayedItemPrice) => {
        requestToOrderPage.getSubTotal(productDetails.productName.replace('"', "")).then((displayedItemSubtotal) => {
            expect(displayedItemSubtotal).to.eq(displayedItemPrice * quantity);
        });
    });
});

Then('user remove added product from request to order page with below message', (dataTable) => {
    const expectedMessage = dataTable.rawTable[1].toString();

    //click on remove link
    requestToOrderPage.clickOnRemoveLink(productDetails.productName.replace('"', ""));

    //validate remove item success message
    requestToOrderPage.getItemRemoveMessage().then((displayedMessage) => {
        expect(displayedMessage).to.eq(expectedMessage);
    });
});

Then('the following item details should be displayed on the request order page', (dataTable) => {
    const validLabel = ['Product Price', 'Product Qty', 'Product SKU'];
    
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the request to order 
        switch (label) {
            case 'product price':
                requestToOrderPage.getProductPrice(productDetails.productName.replace('"', "")).then((displayedProductPrice) => {
                    expect(displayedProductPrice).to.be.equal(productDetails.productPrice);
                });
                break;
            case 'product qty':
                requestToOrderPage.getProductQty(productDetails.productName.replace('"', "")).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'product sku':
                requestToOrderPage.getSkuCode(productDetails.productName.replace('"', "")).then((displayedProductSku) => {
                    expect(displayedProductSku).to.be.equal(productDetails.productSKU.toLowerCase());
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

Then('user should be able to see below request to order details', (dataTable) => {
    let requestToOrderNumber;
    
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const label = dataTable.rawTable[i][1].toLowerCase();
            const expectedValue = dataTable.rawTable[i][2];

            //validate request to order details
            if (testingCountry === country) {
                switch (label) {
                    case 'request to order':
                        requestToOrderNumber = expectedValue;
                        cy.validateDisplayedText(requestToOrderPage.getRequestOrderNo(requestToOrderNumber), expectedValue);
                        break;
                    case 'name':
                        cy.validateDisplayedText(requestToOrderPage.getRequestName(requestToOrderNumber), expectedValue);
                        break;
                    case 'email address':
                        cy.validateDisplayedText(requestToOrderPage.getRequestEmail(requestToOrderNumber), expectedValue);
                        break;
                    case 'created':
                        cy.validateDisplayedText(requestToOrderPage.getRequestCreatedAt(requestToOrderNumber), expectedValue);
                        break;
                    case 'no of items':
                        cy.validateDisplayedText(requestToOrderPage.getRequestItemCount(requestToOrderNumber), expectedValue);
                        break;
                    case 'status':
                        cy.validateDisplayedText(requestToOrderPage.getRequestStatus(requestToOrderNumber), expectedValue);
                        break;
                    case 'link':
                        cy.validateDisplayedText(requestToOrderPage.getRequestViewLink(requestToOrderNumber), expectedValue);
                        requestToOrderPage.clickOnViewLink(requestToOrderNumber);
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid.`);
                }
            }
        }
    });
});

When('user collect no of items details from request to order list page for below order number', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const number = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {
                //collect item no details
                requestToOrderPage.getRequestItemCount(number).then((displayedItemCount) => {
                    expectedNumberOfItems = displayedItemCount.text().trim();
                });

            }
        }
    });
});

Then('the number of added items on the request to order details page should be the same as the item count', () => {
    //validate the number of added products with item count
    requestToOrderPage.getAddedProductsList().then((productsList) => {
        const actualNumberOfItems = productsList.length;
        expect(Number(actualNumberOfItems)).to.eq(Number(expectedNumberOfItems));
    });
});

When('user click on view link of below order number', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const number = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {
                //click on the view link
                requestToOrderPage.clickOnViewLink(number);
            }
        }
    });
});

Then('the product ship by date of request to order page should be the same as displayed on the product description page', () => { 
    //validate that the displayed product ship by date should be the same as on the PDP
    requestToOrderPage.getProductShipByDate(productDetails.productName.replace('"', "")).then((displayedShipByDate) => {
        expect(displayedShipByDate).to.equal(productDetails.shipByDate);
    });
});