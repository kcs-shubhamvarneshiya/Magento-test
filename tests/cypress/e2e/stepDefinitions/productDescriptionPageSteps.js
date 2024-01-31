const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import ProductListPage from '../pageObjects/productListPage';
import ProductDescriptionPage from '../pageObjects/productDescriptionPage';
import LoginPage from '../pageObjects/loginPage';
import HeaderPage from '../pageObjects/headerPage';
import ShoppingCartPage from '../pageObjects/shoppingCartPage';
import ProjectsPage from '../pageObjects/projectsPage';

const productList = new ProductListPage();
const pdp = new ProductDescriptionPage();
const login = new LoginPage();
const header = new HeaderPage();
const shoppingCart = new ShoppingCartPage();
const project = new ProjectsPage();

let productDetails, bulbDetails;

When('user collect the following details of {string} product from the list', (productCode, dataTable) => {
    const validLabel = ['Product Name', 'Price', 'Original Price', 'Trade Price', 'Designer Name'];
    productDetails = {};

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //collect the product details displayed on the list
        switch (label) {
            case 'product name':
                productList.getProductName(productCode).then((displayedProductName) => {
                    productDetails.productName = displayedProductName;
                });
                break;
            case 'price':
                productList.getProductSalePrice(productCode).then((displayedProductSalePrice) => {
                    productDetails.productSalePrice = displayedProductSalePrice;
                });
                break;
            case 'original price':
                productList.getProductOriginalPrice(productCode).then((displayedProductOriginalPrice) => {
                    productDetails.productOriginalPrice = displayedProductOriginalPrice;
                });
                break;
            case 'trade price':
                productList.getProductSalePrice(productCode).then((displayedProductTradePrice) => {
                    productDetails.productTradePrice = displayedProductTradePrice;
                });
                break;
            case 'designer name':
                productList.getProductDesignerName(productCode).then((displayedDesignerName) => {
                    productDetails.designerName = displayedDesignerName;
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

When('user click on the {string} product', (productCode) => {
    //click on the product from the product list page
    productList.clickProductCode(productCode);
});

Then('the product description page of {string} should get opened', (productCode) => {
    //validate whether the pdp page title contains product code or not
    pdp.getPDPTitle().should('contain', productCode);
});

Then('the following product details should be the same as displayed on the list', (dataTable) => {
    const validLabel = ['Product Name', 'Price', 'Original Price', 'Trade Price', 'Designer Name'];

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details should be the same as displayed on the list
        switch (label) {
            case 'product name':
                pdp.getProductName().then((displayedProductName) => {
                    expect(displayedProductName).to.be.equal(productDetails.productName);
                });
                break;
            case 'price':
                pdp.getProductSalePrice().then((displayedProductSalePrice) => {
                    expect(displayedProductSalePrice).to.be.equal(productDetails.productSalePrice);
                });
                break;
            case 'original price':
                pdp.getProductOriginalPrice().then((displayedProductOriginalPrice) => {
                    expect(displayedProductOriginalPrice).to.be.equal(productDetails.productOriginalPrice);
                });
                break;
            case 'trade price':
                pdp.getProductSalePrice().then((displayedProductTradePrice) => {
                    expect(displayedProductTradePrice).to.be.equal(productDetails.productTradePrice);
                });
                break;
            case 'designer name':
                pdp.getDesignerName().then((displayedDesignerName) => {
                    expect(displayedDesignerName).to.be.equal(productDetails.designerName);
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

When('user click on save to project link', () => {
    // click on save to project
    pdp.clickOnSaveToProject();
});

Then('user redirect to login page which has below title', (dataTable) => {
    const loginTitle = dataTable.rawTable[1].toString();

    // validate whether user redirect to login page or not
    login.getLoginPageTitle().should('eq', loginTitle);
});

Then('the following validation message should be displayed on login page', (dataTable) => {
    const validationMessage = dataTable.rawTable[1].toString();

    // validate the validation message displayed on the login page
    login.getValidationMessage().should('have.text', validationMessage);
});

When('user navigate to product description page of {string} product', (productCode) => {
    // validate whether the pdp page title contains product code or not
    pdp.getPDPTitle().should('contain', productCode);
});

When('user collect the following details of product from the product description page', (dataTable) => {
    const validLabel = ['Product Name', 'Product Price', 'Product Qty', 'Finish', 'Product SKU', 'Ship By Date'];
    productDetails = {};

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //collect the product details displayed on the product description page
        switch (label) {
            case 'product name':
                pdp.getProductName().then((displayedProductName) => {
                    productDetails.productName = displayedProductName;
                });
                break;
            case 'product price':
                pdp.getProductSalePrice().then((displayedProductPrice) => {
                    productDetails.productPrice = displayedProductPrice;
                });
                break;
            case 'product qty':
                pdp.getProductQty().then((displayedProductQty) => {
                    productDetails.productQty = displayedProductQty;
                });
                break;
            case 'finish':
                pdp.getSelectedDropdownOption(row.label).then((displayedProductFinish) => {
                    productDetails.productFinish = displayedProductFinish;
                });
                break;
            case 'product sku':
                pdp.getSkuCode().then((displayedProductSKU) => {
                    productDetails.productSKU = displayedProductSKU;
                });
                break;
            case 'ship by date':
                pdp.getShipByDate().then((displayedShipByDate) => {
                    productDetails.shipByDate = displayedShipByDate;
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
}); export { productDetails }

Then('the following item details should be displayed on the mini cart', (dataTable) => {
    //mouseover on mini cart icon
    header.mouseoverMiniCartIcon();

    dataTable.hashes().forEach((row) => {
        const validLabel = ['Product Name', 'Product Price', 'Product Qty', 'Finish', 'Bulb Name', 'Bulb Price', 'Bulb Qty'];
        const label = row.label.toLowerCase();

        //validate the product details displayed on the mini cart
        switch (label) {
            case 'product name':
                header.getMiniCartItemNameList().then((displayedItemNameList) => {
                    expect(displayedItemNameList).to.be.include(productDetails.productName);
                });
                break;
            case 'product price':
                header.getMiniCartItemPrice(productDetails.productName).then((displayedProductPrice) => {
                    expect(displayedProductPrice).to.be.equal(productDetails.productPrice);
                });
                break;
            case 'product qty':
                header.getMiniCartItemQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                header.clickMiniCartItemSeeDetailsLink(productDetails.productName);
                header.getMiniCartItemVariant(productDetails.productName, label).then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            case 'bulb name':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        header.getMiniCartItemNameList().then((displayedItemNameList) => {
                            expect(displayedItemNameList).to.be.include(bulbDetails.bulbName);
                        });
                    }
                });
                break;
            case 'bulb price':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        header.getMiniCartItemPrice(bulbDetails.bulbName).then((displayedBulbPrice) => {
                            cy.convertPriceToNumber(displayedBulbPrice, '$').then((displayedBulbPriceAsNumber) => {
                                cy.bulbPricePerUnit(bulbDetails.bulbPrice, bulbDetails.bulbQty).then((bulbPricePerUnit) => {
                                    expect(parseFloat(displayedBulbPriceAsNumber).toFixed(2)).to.be.equal(bulbPricePerUnit);
                                });
                            });
                        });
                    }
                });
                break;
            case 'bulb qty':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        header.getMiniCartItemQty(bulbDetails.bulbName).then((displayedBulbQty) => {
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

Then('user should be able to see below sku code', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const usSkuCode = dataTable.rawTable[i][1];

            if (testingCountry === country) {
                // validate whether sku code gets changed as per the selected variant or not 
                pdp.getSkuCode().then((actualSkuCode) => {
                    expect(actualSkuCode).to.be.equal(usSkuCode);
                });
                break;
            }
        }
    });
});

Then('user should be able to see below success message of save to project or quote', (dataTable) => {
    const expectedSuccessMessage = dataTable.rawTable[1].toString().toLowerCase();

    // validate success message displayed on the pdp page 
    pdp.getSaveToProjectOrQuoteSuccessMessage().then((actualMessage) => {
        expect(actualMessage).to.be.equal(expectedSuccessMessage);
    });
});

When('user unselect the bulb checkbox', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //unselect the bulb checkbox
            pdp.unselectBulbCheckbox();
        } else {
            cy.log('Bulb option is not available for the ' + testingCountry);
        }
    });
});

When('user click on the add to cart button from the product description page', () => {
    //click on the add to cart button
    pdp.clickAddToCart();
});

When('user collect the following details of bulb from the add bulb popup', (dataTable) => {
    const validLabel = ['Bulb Name', 'Bulb Price', 'Bulb Qty'];
    bulbDetails = {};

    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            dataTable.hashes().forEach((row) => {
                const label = row.label.toLowerCase();

                //collect the bulb details displayed on the add bulb popup
                switch (label) {
                    case 'bulb name':
                        pdp.getBulbName().then((displayedBulbName) => {
                            bulbDetails.bulbName = displayedBulbName;
                        });
                        break;
                    case 'bulb price':
                        pdp.getBulbPrice().then((displayedBulbPrice) => {
                            bulbDetails.bulbPrice = displayedBulbPrice;
                        });
                        break;
                    case 'bulb qty':
                        pdp.getBulbQty().then((displayedBulbQty) => {
                            bulbDetails.bulbQty = displayedBulbQty;
                        });
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
                }
            });
        } else {
            cy.log('Bulb option is not available for the ' + testingCountry);
        }
    });
}); export { bulbDetails }

When('user close the add bulb popup', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //close the bulb popup
            pdp.closeAddBulbPopup();
        } else {
            cy.log('Bulb option is not available for the ' + testingCountry);
        }
    });
});

Then('the following add to cart success message should be displayed', (dataTable) => {
    const successMessage = dataTable.rawTable[1].toString();

    //validate the add to cart success message
    pdp.getProductAddedMsgText().then((displayedSuccessMsg) => {
        expect(displayedSuccessMsg.toLowerCase()).to.contain(successMessage.toLowerCase());
    });
});

Then('the bulb name should not be displayed on the mini cart', () => {
    //mouse hover on the mini cart
    header.mouseoverMiniCartIcon();

    //validate the bulb name should not be displayed on the mini cart
    header.getMiniCartItemNameList().then((displayedItemNameList) => {
        expect(displayedItemNameList).not.to.be.include(bulbDetails.bulbName);
    });
});

When('user click on the add to cart button from the add bulb popup', () => {
    cy.getCountry().then((testingCountry) => {
        if (testingCountry === 'us') {
            //click on the add to cart button displayed on the add bulb popup
            pdp.clickAddToCartFromAddBulbPopup();
            pdp.waitTillAddBulbPopupDisappear();
        } else {
            cy.log('Bulb option is not available for the ' + testingCountry);
        }
    });
});

Then('user redirect to shopping cart page which has below title', (dataTable) => {
    const shoppingCartTitle = dataTable.rawTable[1].toString();

    //validate whether user redirect to shopping cart page or not
    shoppingCart.getShoppingCartPageTitle().should('eq', shoppingCartTitle);
});

Then('the following item details should be displayed on the shopping cart page', (dataTable) => {
    const validLabel = ['Product Name', 'Product Price', 'Product Qty', 'Finish', 'Product SKU', 'Bulb Name', 'Bulb Price', 'Bulb Qty'];

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the item details displayed on the shopping cart
        switch (label) {
            case 'product name':
                shoppingCart.getItemNameList().then((displayedItemNameList) => {
                    expect(displayedItemNameList).to.be.include(productDetails.productName);
                });
                break;
            case 'product price':
                shoppingCart.getItemPrice(productDetails.productName).then((displayedProductPrice) => {
                    expect(displayedProductPrice).to.be.equal(productDetails.productPrice);
                });
                break;
            case 'product qty':
                shoppingCart.getItemQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                shoppingCart.getItemVariant(productDetails.productName, label).then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            case 'product sku':
                shoppingCart.getItemSKU(productDetails.productName).then((displayedProductSku) => {
                    expect(displayedProductSku).to.be.equal(productDetails.productSKU);
                });
                break;
            case 'bulb name':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        shoppingCart.getItemNameList().then((displayedItemNameList) => {
                            expect(displayedItemNameList).to.be.include(bulbDetails.bulbName);
                        });
                    }
                });
                break;
            case 'bulb price':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        shoppingCart.getItemPrice(bulbDetails.bulbName).then((displayedBulbPrice) => {
                            cy.convertPriceToNumber(displayedBulbPrice, '$').then((displayedBulbPriceAsNumber) => {
                                cy.bulbPricePerUnit(bulbDetails.bulbPrice, bulbDetails.bulbQty).then((bulbPricePerUnit) => {
                                    expect(parseFloat(displayedBulbPriceAsNumber).toFixed(2)).to.be.equal(bulbPricePerUnit);
                                });
                            });
                        });
                    }
                });
                break;
            case 'bulb qty':
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        shoppingCart.getItemQty(bulbDetails.bulbName).then((displayedBulbQty) => {
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

When('user click on the create new project link', () => {
    //click on the add to project toggle button
    pdp.clickAddToProjectToggleButton();

    //click on the create new project link
    pdp.clickCreateNewProjectLink();
});

When('user enter {string} project name', (projectName) => {
    //enter project name
    pdp.enterProjectName(projectName);
});

When('user click on the create new project button', () => {
    //click on the create new project button
    pdp.clickCreateNewProjectButton();
});

Then('the following success messages of adding a product to a new project should be displayed', (dataTable) => {
    const projectSavedMsg = dataTable.rawTable[1].toString().toLowerCase();
    const productAddedToProjectMsg = dataTable.rawTable[2].toString().toLowerCase();

    //validate success messages after click on save to new project 
    pdp.getSaveToProjectOrQuoteSuccessMessage().then((actualMessage) => {
        expect(actualMessage).to.contain(projectSavedMsg);
        expect(actualMessage).to.contain(productAddedToProjectMsg);
    });
});

When('user delete {string} from {string} page if present', (projectName, page) => {
    //go to the projects page
    header.gotoProfileOptionPage(page);

    //delete a single project
    project.deleteSpecificProject(projectName);
});

When('user add {int} quantity of product', (numberOfProductQuantity) => {
    // add a product quantity
    pdp.enterProductQuantity(numberOfProductQuantity);
});

When('user clear added items from the cart', () => {
    // clear the added item from cart     
    header.getMiniCartCount().then((totalCount) => {

        if (totalCount > 0) {
            cy.scrollTo('top');
            header.clickMiniCartIcon();
            shoppingCart.clickOnClearCartButton();
            shoppingCart.clickOnClearCartPopupOkButton();
            shoppingCart.getCartEmptyMessage().should('be.visible');
        } else {
            cy.log('Cart is already empty');
        }
    });
});

Then('the following quantity should be displayed on the mini cart for {string} product', (productName, dataTable) => {
    const expectedQuantity = dataTable.rawTable[1].toString();

    // mouse hover on the mini cart
    header.mouseoverMiniCartIcon();

    // validate the product quantity on the mini cart
    header.getMiniCartItemQty(productName).then((actualQuantity) => {
        expect(actualQuantity).to.be.equal(expectedQuantity);
    });
});

When('user click add to quote button', () => {
    // click on add to quote button
    pdp.clickOnAddToQuote();
});

When('user select {string} from add to quote', (quoteName) => {
    // select quote 
    pdp.selectQuoteFromAddToQuote(quoteName);
});

Then('user should be able to see below finish selected', (dataTable) => {
    let field = dataTable.rawTable[0].toString();
    let expectedValue = dataTable.rawTable[1].toString();

    // validate the selected finish dropdown value 
    pdp.getSelectedDropdownOption(field).then((selectedValue) => {
        expect(selectedValue.toLowerCase()).to.eq(expectedValue.toLowerCase());
    });
});

When('user click new quote link', () => {
    //click on the add to new quote link
    pdp.clickOnAddNewQuote();
});

When('user click on the update quote button', () => {
    //click on the update quote
    pdp.clickOnUpdateQuote();
});

When('user click on request order button from the product description page', () => {
    //click on request to order button
    pdp.clickOnRequestOrderButton();
});

When('user click on view your request to order button from pop up', () => {
    //click on view your request to order button
    pdp.clickOnViewYourRequestOrder();
});

When('user wait for {int} seconds', (sec) => {
    //wait for given seconds
    cy.wait(sec * 1000);
});

When('user click on the update project button', () => {
    //click on the update project
    pdp.clickOnUpdateToProject();
});

Then('the ship by date should be displayed on the product description page', () => {
    //validate the ship by date should be displayed
    pdp.validateShipByDateVisibility();
});

When('user click on {string} specifications', (specifications) => {
    //click on the inch/cm specifications
    if (specifications.toLowerCase() === 'in') {
        pdp.clickOnInchSpecification();   
    } else if (specifications.toLowerCase() === 'cm') {
        pdp.clickOnCmSpecification();
    } else {
        throw new Error(`The provided '${specifications}' specifications is not valid. Valid name is in or cm.`);
    }
});

Then('user should be able to see below inch specifications', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedInchSpec = dataTable.rawTable[i][1].toString().replace(/,/g, '').replace(/ /g, '');
           
            if (testingCountry === country) {
                //validate inch specification details
                pdp.getInchSpecDetails().then((displayedInchSpecDetails) => {
                    expect(displayedInchSpecDetails.replace(/,/g, '').replace(/ /g, '')).to.be.equal(expectedInchSpec);
                });
            }
        }
    }); 
});

Then('user should be able to see below cm specifications', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedCmSpec = dataTable.rawTable[i][1].toString().replace(/,/g, '').replace(/ /g, '');
            
            if (testingCountry === country) {
                //validate cm specification details
                pdp.getCmSpecDetails().then((displayedCmSpecDetails) => {
                    expect(displayedCmSpecDetails.replace(/,/g, '').replace(/ /g, '')).to.be.equal(expectedCmSpec);
                });
            }
        }
    }); 
});

Then('user should be able to see olapic section on the product description page', () => {
    //validate the olapic section visible or not
    pdp.getOlapicSectionHeader().should('be.visible');
    pdp.getOlapicSectionImages().should('be.visible');
});

Then('the request to order button should be displayed on the product description page', () => {
    //validate the request to order button should be displayed
    pdp.getRequestOrderButton().should('be.visible');
});

Then('the product description page of the following sku should get opened', (dataTable) => {
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const skuCode = row.skuCode;

            if (testingCountry === country) {
                //validate the page title should contain the given SKU
                pdp.getPDPTitle().should('contain',skuCode);
            }
        });
    });
});

When('user click on below product image from the product description page', (dataTable) => {  
    cy.getCountry().then((testingCountry) => {   
        dataTable.hashes().forEach((row) => {
            const country = row.country.toLowerCase();
            const imageName = row.image;

            if (testingCountry === country) {
                //click on the given product image
                pdp.clickOnProductImage(imageName);
            }
        });
    });
});

When('user click on the view series link from the product description page', () => {
    //click on the view series link
    pdp.clickOnViewSeries();
});

Then('user should see {string} section', (sectionName) => {
    //validate the section name
    pdp.getMoreFromSeriesSectionHeader().then((displayedHeader) => {
        expect(displayedHeader.toLowerCase()).to.eq(sectionName.toLowerCase());
    });
});

Then('the product image, name, price, and designer name should be displayed in the more from series section', () => {
    //verify product image, name, price, and designer name in the section
    pdp.getMoreFromSeriesProductImage().should('be.visible');
    pdp.getMoreFromSeriesProductName().should('be.visible');
    pdp.getMoreFromSeriesProductDesignerName().should('be.visible');
    pdp.getMoreFromSeriesProductPrice().should('be.visible');
});

When('user click on the first product name from the more series section', () => {
    //click on the first product name
    pdp.clickOnMoreFromSeriesFirstProductName();
});

When('user collect the first product name from the more series section', () => {
    productDetails = {};

    //collect first product name
    pdp.getMoreFromSeriesFirstProductName().then((displayedName) => {
        productDetails.productName = displayedName;
    });
});

Then('user navigate to the product description page of first product', () => {
    //validate pdp title
    pdp.getPDPTitle().should('contain', productDetails.productName);
});

Then('the product image, name, price, trade price and designer name should be displayed in the more from series section', () => {
    //verify product image, name, price, trade price and designer name in the section
    pdp.getMoreFromSeriesProductImage().should('be.visible');
    pdp.getMoreFromSeriesProductName().should('be.visible');
    pdp.getMoreFromSeriesProductDesignerName().should('be.visible');
    pdp.getMoreFromSeriesProductPrice().should('be.visible');
    pdp.getMoreFromSeriesProductOriginalPrice().should('be.visible');
});