const pageHeaderRoot = 'div.page-title-wrapper';
const pageHeader = 'span.base';
const productName = 'a.product-item-link'; 
const breadcrumbValue = '.breadcrumbs > ul > li';
const sortByDropdown = '.block-filters-wrapper > .toolbar > .viewer > .toolbar-sorter > #sorter';
const filterName = '.filter-options-title';
const filterOption = '.filter-options-item.allow.active li[class="item "] > a > span';
const selectedFilterNameText = '.amshopby-filter-name';
const selectedFilterValueText = '.amshopby-filter-value';
const productList = 'ol[class="products list items product-items"] > li[class="item product product-item"]';
const filterPriceRange = 'li[data-container="price"]';
const selectedPrice = '[class="am-filter-items-attr_price"] > form > input';
const priceFilter = '[class="am-slider ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content -loaded"] > span:nth-child(2)';
const skuCode = 'span.product-sku';
const productDetails = 'div[class="product details product-item-details"]';
const productSalePrice = 'span[data-price-type="finalPrice"] > span.price';
const productOriginalPrice = 'span[data-price-type="oldPrice"] > span.price';
const nextPage = 'li.item.pages-item-next > a[title="Next"]';
const pagination = 'ul.items.pages-items';
const productImage = 'a span.product-image-wrapper img[src]:not([src=""])'; 
const productDesignerName = 'p.designer';
const productVariantRoot = 'div.product-item-variation-carousel-wrapper';
const productImageSection = 'a.product.photo.product-item-photo';
const productVariantLink = 'a.view-more';

class ProductListPage {

    getPageHeader () {
        return cy.get(pageHeaderRoot).find(pageHeader);
    }

    clickProductName (value) {
        cy.get(productName).contains(value, {matchCase: false}).scrollIntoView().click({force: true});
        cy.waitForSometime();
    }

    getPLPTitle () {
        cy.waitForSometime();
        return cy.title();
    }

    getProductListPageUrl () {
       return cy.url();
    }

    getBreadcrumbValue () {
        return cy.getListElement(breadcrumbValue, " / ");       
    }

    getSortByDropdownValue () {
        return cy.get(sortByDropdown).find(':selected').invoke('text').then((selectedText) => {      
            let selectedDropdownValue = selectedText.trim();
            return selectedDropdownValue;
        });
    }

    clickOnFilterOfProductListPage (filter) {
        cy.waitForSometime();
        cy.contains(filterName, filter, {matchCase: false}).click();
    }

    clickFilterOption (filterValue) {
        cy.contains(filterOption, filterValue, {matchCase: false}).click();
        cy.wait(4000);
    }

    getSelectedFilterOption () {
        cy.waitForSometime();
        return cy.get(selectedFilterNameText).invoke('text').then((filterName) => {
            let selectedFilterNameText = filterName.trim();    
            return cy.get(selectedFilterValueText).invoke('text').then((filterValue) => {
                let selectedFilterValueText = filterValue.trim();
                return selectedFilterNameText + " : " + selectedFilterValueText;
            });
        });       
    }
    
    getAllFilterText () {      
        cy.get(filterName).should('be.visible');
        return cy.getListElement(filterName, ",");      
    }

    getProductList () {
        return cy.get(productList);
    }

    selectPriceFilterBySlider () {
        cy.get(priceFilter).invoke("attr","style","left: 41.5682%; background: rgb(255, 85, 2);")
        .trigger("mousedown", { which: 1 })
        .trigger("mousemove")
        .trigger("mouseup");
    }

    getFilterPriceRange () {
        return cy.get(filterPriceRange) 
        .invoke('attr', 'data-value') 
        .then((filterText) => {
            const priceText = filterText.trim();
            const filterPriceRange = priceText.replace(/\.00/g, "");           
            return filterPriceRange;
        });
    }

    getPriceFilter () {
        return cy.get(selectedPrice) 
        .invoke('attr', 'value') 
        .then((price) => {
            const selectedPriceRange = price.trim();          
            return selectedPriceRange;
        });
    }

    getProductName (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productName).should('be.visible')
        .scrollIntoView().then((displayedProductName) => {
            return displayedProductName.text().trim().toLowerCase();
        });
    }

    getProductSalePrice (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productSalePrice)
        .should('be.visible').then((displayedSalePrice) => {
            return displayedSalePrice.text().trim();
        });
    }

    clickProductCode (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productName).click({force: true});
        cy.waitForSometime();
    }

    getProductOriginalPrice (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productOriginalPrice)
        .then((displayedOriginalPrice) => {
            return displayedOriginalPrice.text().trim();
        });
    }

    getSelectedFilterName (filterName) {
        cy.waitForSometime();
        cy.wait(2000);
        return cy.contains(selectedFilterNameText, filterName, { matchCase: false }).should('be.visible').invoke('text')
        .then((displayedFilterName) => {
            return displayedFilterName.trim();
        });       
    }

    getSelectedFilterValue (filterName) {
        return cy.contains(selectedFilterNameText, filterName, { matchCase: false }).siblings(selectedFilterValueText)
        .should('be.visible').invoke('text').then((displayedFilterValue) => {
            return displayedFilterValue.trim();
        });       
    }

    getSelectedPriceFilterValue (filterName) {
        return cy.contains(selectedFilterNameText, filterName, { matchCase: false }).siblings(selectedFilterValueText);     
    }

    getNextPage () {
        cy.get(pagination).scrollIntoView();
        return cy.get(nextPage);
    }

    getProductCodeList () {
        return cy.get(skuCode);
    }

    getAllProductName () {
        return cy.get(productList).find(productName);     
    }

    getAllProductImage () {
        return cy.get(productList).find(productImage);     
    }

    getProductDesignerName (skuCodeValue) {
        cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).scrollIntoView();
        return cy.contains(skuCode, skuCodeValue, {matchCase: false}).parent(productDetails).find(productDesignerName)
        .should('be.visible').then((displayedDesignerName) => {
            return displayedDesignerName.text().trim();
        });
    }

    mouseHoverProductImage (baseSKUCode) {
        cy.waitForSometime();
        cy.contains(skuCode, baseSKUCode, {matchCase: false}).parent(productDetails).siblings(productImageSection)
        .trigger('mouseover');
    }

    mouseHoverProductVariantSKUCode (baseSKUCode, variantSKUCode) {
        this.mouseHoverProductImage(baseSKUCode);
        cy.contains(skuCode, baseSKUCode, {matchCase: false}).parent(productDetails).siblings(productVariantRoot)
        .find('a[data-clp-finish]:is([data-clp-sku*="'+variantSKUCode+'" i])').trigger('mouseover', {force: true});
        cy.wait(3000);
    }

    getBaseProductImage (productNameValue, imageName) {
        return cy.contains(productName, productNameValue, {matchCase: false}).parents(productDetails).siblings(productImageSection)
        .find('span.product-image-wrapper > img[src]:is([src*="'+imageName+'" i])');     
    }

    getProductSKUCode (productNameValue) {
        return cy.contains(productName, productNameValue, {matchCase: false}).parents(productDetails).find(skuCode)
        .should('be.visible');
    }

    getProductPrice (productNameValue) { 
        return cy.contains(productName, productNameValue, {matchCase: false}).parents(productDetails).find(productSalePrice);
    }

    getProductVariantLink (productNameValue) { 
        return cy.contains(productName, productNameValue, {matchCase: false}).parents(productDetails).find(productVariantLink)
        .should('be.visible');
    }

    getProductOldPrice (productNameValue) {
        return cy.contains(productName, productNameValue, {matchCase: false}).parents(productDetails).find(productOriginalPrice);
    }

} export default ProductListPage