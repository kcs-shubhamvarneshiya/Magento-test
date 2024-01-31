const searchResultsText = 'span[data-ui-id="page-title-wrapper"]';
const productList = 'ol[class="products list items product-items"] > li[class="item product product-item"]';
const productDetails = 'div[class="product details product-item-details"]';
const productName = 'a.product-item-link';
const designerName = 'p.designer';
const skuCode = 'span.product-sku';
const productSalePrice = 'span[data-price-type="finalPrice"] > span.price';
const productOriginalPrice = 'span[data-price-type="oldPrice"] > span.price';
const viewMoreLink = 'a.view-more';
const pagination = 'div.pagination ul.items.pages-items';

class SearchResultsPage { 

    getSearchPageUrl () {
        return cy.url();
    }  

    getSearchResultsText () {
        return cy.get(searchResultsText);
    }

    getProductList () {
        return cy.get(productList);
    }

    getProductName (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productName).scrollIntoView();
    }

    getDesignerName (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(designerName)
        .should('be.visible').scrollIntoView();
    }

    getProductCode (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(skuCode);
    }

    getProductSalePrice (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productSalePrice);
    }

    getProductOriginalPrice (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productOriginalPrice);
    }

    getProductViewMoreLink (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(viewMoreLink);
    }

    getPagination () {
        return cy.get(pagination).scrollIntoView();
    }

} export default SearchResultsPage