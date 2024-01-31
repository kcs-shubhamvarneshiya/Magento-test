const { Then, When } = require ('@badeball/cypress-cucumber-preprocessor');

import InventoryPage from '../pageObjects/inventoryPage';
import HeaderPage from '../pageObjects/headerPage';

const inventoryPage = new InventoryPage();
const header = new HeaderPage();

let productDetails;

Then('user should be able to see a search button', () => {
    //validate that the search button should be displayed
    inventoryPage.getSearchButton().should('be.visible');
});

Then('the following search suggestions text should be displayed', (dataTable) => {
    const expectedText = dataTable.rawTable[1].toString();

    //validate the search suggestions text
    inventoryPage.getSearchText().should('have.text', expectedText);
});

When('user enter the {string} sku in the search box', (skuCode) => {
    //enter the sku in search box
    inventoryPage.enterSKU(skuCode);
});

When('user click on the search button', () => {
    //click on the search button
    inventoryPage.clickSearchButton();
});

Then('user should be able to see {int} matching search results', (expectedNumberOfRecords) => {
    //validate the number of search results
    inventoryPage.getSearchResultsRows().then((actualNumberOfRecords) => {
       expect(actualNumberOfRecords).to.be.equal(expectedNumberOfRecords);
    });
});

Then('user should be able to see the following sku and image of the following sku on the search results', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const expectedSKUCode = row.sku.toLowerCase();

        //validate all SKU which displayed on the search results list
        inventoryPage.getSearchResultsSKUCodes().then((actualSKUCodes) => {
            expect(actualSKUCodes.toLowerCase()).to.include(expectedSKUCode);
        });

        //validate the image of searched sku should be displayed
        inventoryPage.getImageOfSearchedSKU(expectedSKUCode).should('be.visible');
    });
});

Then('user should be able to see a request to order link with underlined for the following sku', (dataTable) => {
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const skuCode = row.skuCode;

            if (testingCountry === country) {
                //validate the request to order link should be displayed with underlined
                inventoryPage.getRequestToOrderLink(skuCode).should('be.visible').then((requestToOrderLink) => {
                    cy.wrap(requestToOrderLink).invoke('attr','style').should('contain', 'underline');
                });
            }
        });
    });
});

Then('user should be able to see the add to cart button as disabled', () => {
    //validate the add to cart button should be displayed as disabled
    inventoryPage.getAddToCartButton().should('be.visible').should('have.attr', 'disabled');
});

When('user click on the request to order link of the following sku', (dataTable) => {
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const skuCode = row.skuCode;

            if (testingCountry === country) {
                //click on the request to order link of given SKU
                inventoryPage.clickRequestToOrderLink(skuCode);
            }
        });
    });
});

When('user enter the following sku in the search box', (dataTable) => {
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const skuCode = row.skuCode;

            if (testingCountry === country) {
                //enter the SKU in search box
                inventoryPage.enterSKU(skuCode);
            }
        });
    });
});

When('user enter the {int} qty for the {string} sku', (qty,skuCode) => {
    //enter the given product qty
    inventoryPage.enterProductQty(qty, skuCode);
});

Then('user should be able to see the add to cart button as enabled', () => {
    //validate the add to cart button should be displayed as enabled
    inventoryPage.getAddToCartButton().should('be.visible').should('not.have.attr', 'disabled');
});

When('user click on the add to cart button from the inventory page', () => {
    //click on the add to cart button
    inventoryPage.clickAddToCartButton();
});

Then('the following success message should be displayed on the {string} page', (page, dataTable) => {
    dataTable.hashes().forEach((row) => {
        const expectedMessage = row.message;

        //validate the success message should be displayed on the given page
        inventoryPage.getPageTitle().should('have.string', page);
        inventoryPage.getSuccessMessage().then((displayedMessage) => {
            expect(displayedMessage).to.be.equal(expectedMessage)
        });
    });
});

When('user collect the following details of {string} sku from the inventory page', (skuCode, dataTable) => {
    const validLabel = ['Product Name', 'Product Qty'];
    productDetails = {};

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //collect the product details displayed on the inventory list
        switch (label) {
            case 'product name':
                inventoryPage.getProductName(skuCode).then((displayedProductName) => {
                    productDetails.productName = displayedProductName.toLowerCase();
                });
                break;
            case 'product qty':
                inventoryPage.getProductQty(skuCode).then((displayedProductQty) => {
                    productDetails.productQty = displayedProductQty;
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

Then('the following product details should be displayed on the mini cart', (dataTable) => {
    const validLabel = ['Product Name', 'Product Qty'];

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the mini cart
        switch (label) {
            case 'product name':
                header.getMiniCartItemNameList().then((displayedItemNameList) => {
                    expect(displayedItemNameList).to.be.include(productDetails.productName);
                });
                break;
            case 'product qty':
                header.getMiniCartItemQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});