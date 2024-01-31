const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import HeaderPage from '../pageObjects/headerPage';
import ProductListPage from '../pageObjects/productListPage';
import SearchResultsPage from '../pageObjects/serachResultsPage';

const header = new HeaderPage();
const productList = new ProductListPage();
const search = new SearchResultsPage();

When('user click on {string} mega menu', (megaMenu) => {
    // click on mega menu of home page
    header.clickOnMegaMenu(megaMenu);
});

Then('product list page of {string} mega menu should get opened', (megaMenu) => {
    // validate product list page title
    productList.getPLPTitle().should('contain', megaMenu);
});

Then('the below breadcrumb value should be displayed on the product list page', (dataTable) => {
    let expectedBreadcrumbValue = dataTable.raw().slice(1).map((row) => row[0].trim()).toString();

    // validate breadcrumb value on product list page
    productList.getBreadcrumbValue().then((actualBreadcrumbValue) => {
        expect(actualBreadcrumbValue).to.eq(expectedBreadcrumbValue);   
    });
});

Then('the below option of sort by dropdown should be displayed as by default selected', (dataTable) => {
    let expectedDefaultSelectedValue = dataTable.raw().slice(1).map((row) => row[0]).toString();

    // validate default selected value of sort by dropdown on product list page
    productList.getSortByDropdownValue().then((selectedValue) => {
        expect(selectedValue.toUpperCase()).to.eq(expectedDefaultSelectedValue);  
    });
});

When('user navigate to the product list page of {string} mega menu', (megaMenu) => {   
    // validate product list page title
    productList.getPLPTitle().should('contain', megaMenu);
});

Then('user should be able to see below available filters on product list page', (dataTable) => {
    for (let i = 1; i < dataTable.rawTable.length; i++) {
        const expectedFilters = dataTable.rawTable[i].toString(); 

        //validate the expected filter should be displayed on the product list page
        productList.getAllFilterText().then((actualFilters) => {
            expect(actualFilters.toLowerCase()).to.contain(expectedFilters.toLowerCase());  
        });
    }
});

When('user click on {string} filter', (filterName) => {      
    // click on filter of product list page
    productList.clickOnFilterOfProductListPage(filterName);
});

Then('the {string} option should be displayed as selected filter', (expectedSelectedFilterOption) => {     
    // validate selected filter option
    productList.getSelectedFilterOption().then((actualSelectedFilterOption) => {
        expect(actualSelectedFilterOption.toLowerCase()).to.eq(expectedSelectedFilterOption.toLowerCase()); 
    });
});

Then('{int} products should be displayed on the product list page', (numberOfProducts) => {
    // validate 48 products on the product list page
    productList.getProductList().should('have.length',numberOfProducts);
});

When('user move a slider under price filter', () => {
    // select price range by moving slider
    productList.selectPriceFilterBySlider();
});

Then('the price range displayed on the applied filter and price filter popup should be the same', () => {
    // validate price range
    productList.getFilterPriceRange().then((actualPriceRange) => {
        productList.getPriceFilter().then((expectedPriceRange) => {
            expect(actualPriceRange).to.eq(expectedPriceRange); 
        });
    });  
});

When('user select {string} option from given filter', (filterOption) => {     
    // click on filter option 
    productList.clickFilterOption(filterOption);
});

Then('the user should be able to see the following selected multiple filters', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const expectedFilterName = row.filterName;
        const expectedFilterValue = row.filterValue;

        if (expectedFilterName.toLowerCase() === 'price') {
            //validate the selected price filter name and price range should be displayed
            productList.getSelectedFilterName(expectedFilterName).should('equal', expectedFilterName);
            productList.getSelectedPriceFilterValue(expectedFilterName).should('be.visible');
        } else {
            //validate the selected filter name and value
            productList.getSelectedFilterName(expectedFilterName).should('equal', expectedFilterName);
            productList.getSelectedFilterValue(expectedFilterName).should('equal', expectedFilterValue);
        }
    });  
});

Then('the following product details should be displayed on the following product card', (dataTable) => {
    const validLabel = ['Product Name', 'Designer Name', 'SKU Code', 'Price', 'Original Price', 'View More Link', 'Product Card'];
    let skuCode = '';

    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const label = dataTable.rawTable[i][1].toLowerCase();
            const expectedValue = dataTable.rawTable[i][2];          
  
            //validate product details displayed on the product card
            if (testingCountry === country) {
                switch (label) {
                    case 'product card':
                        skuCode = expectedValue;
                        //find the product card of given code
                        cy.findProductCode(skuCode);
                        break;
                    case 'product name':
                        cy.validateDisplayedText(search.getProductName(skuCode), expectedValue);
                        break;
                    case 'designer name':
                        cy.validateDisplayedText(search.getDesignerName(skuCode), expectedValue);
                        break;
                    case 'sku code':
                        cy.validateDisplayedText(search.getProductCode(skuCode), expectedValue);
                        break;
                    case 'price':
                        search.getProductSalePrice(skuCode).should('be.visible');
                        break;               
                    case 'original price':
                        search.getProductOriginalPrice(skuCode).should('be.visible');
                        break;
                    case 'view more link':
                        cy.validateDisplayedText(search.getProductViewMoreLink(skuCode), expectedValue);
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
                }
            }
        }
    });
});

Then('the products should be displayed on the product list page', () => {
    //validate the product name and image should be displayed
    productList.getAllProductName().should('be.visible');
    productList.getAllProductImage().should('be.visible');
});

When('user find the {string} product from the list', (skuCode) => {
    //find the given product code
    cy.findProductCode(skuCode);
});

When('user click on the following sku code from the list', (dataTable) => {
    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const skuCode = dataTable.rawTable[i][1].toLowerCase();

            if (testingCountry === country) { 
                //find the given sku code
                cy.findProductCode(skuCode);
    
                //click on the product 
                productList.clickProductCode(skuCode);
            }      
        }
    });
});

When('user find the following sku code from the list', (dataTable) => {
    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const productSKUCode = dataTable.rawTable[i][1].toLowerCase();

            if (testingCountry === country) { 
                //find the given product sku code
                cy.findProductCode(productSKUCode);
            }      
        }
    });
});

When('user mouse hover on the following variant sku of the following product sku', (dataTable) => {
    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const productSKUCode = dataTable.rawTable[i][1].toLowerCase();
            const variantSKUCode = dataTable.rawTable[i][2].toLowerCase();

            if (testingCountry === country) { 
                //mouse hover on the given variant
                productList.mouseHoverProductVariantSKUCode(productSKUCode, variantSKUCode);
            } 
        }
    });
});

Then('the following details should be displayed on the following product card', (dataTable) => {
    const validLabel = ['Image', 'SKU', 'Price', 'Original Price', 'Variant Link', 'Card'];
    let productName = '';

    cy.getCountry().then((testingCountry) => {        
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const label = dataTable.rawTable[i][1].toLowerCase();
            const expectedValue = dataTable.rawTable[i][2];          
  
            //validate the details displayed on the product card
            if (testingCountry === country) {
                switch (label) {
                    case 'card':
                        productName = expectedValue;
                        break;
                    case 'image':
                        productList.getBaseProductImage(productName, expectedValue).should('be.visible');
                        break;
                    case 'sku':
                        cy.validateDisplayedText(productList.getProductSKUCode(productName), expectedValue);
                        break;
                    case 'price':
                        productList.getProductPrice(productName).should('be.visible');
                        break;               
                    case 'original price':
                        productList.getProductOldPrice(productName).should('be.visible');
                        break;
                    case 'variant link':
                        cy.validateDisplayedText(productList.getProductVariantLink(productName), expectedValue);
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
                }
            }
        }
    });
});