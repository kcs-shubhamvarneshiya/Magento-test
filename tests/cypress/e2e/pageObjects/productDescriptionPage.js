const addToCart = 'button#product-addtocart-button';
const successMessage = 'div[class="page messages"]';
const productName = 'span[data-ui-id="page-title-wrapper"]';
const productSalePrice = 'div.product-info-main > div.product-info-price span[data-price-type="finalPrice"]';
const productOriginalPrice = 'div.product-info-main > div.product-info-price span[data-price-type="oldPrice"]';
const saveToProductLink = 'a[class="action towishlist"] > span';
const productQty = 'input#qty';
const bulbQty = 'ol.products.list.items.product-items div.product-qty__data > span';
const bulbPrice = 'ol.products.list.items.product-items div.product-info-price.hidden-mobile > span';
const skuCode = 'div[class="product attribute sku"] > div[itemprop="sku"]';
const bulbCheckbox = 'input[name="lightbulb[0][id]"]';
const addBulbPopup = 'aside.modal-popup.modal-slide._show';
const addBulbPopupCloseButton = 'button[data-role="closeBtn"]';
const bulbName = 'ol.products.list.items.product-items strong.product.name.product-item-name > a.product-item-link';
const saveToProjectOrQuoteSuccessMessage = 'div[class="messages"] > div[class="message-success success message"] > div';
const addToCartFromAddBulbPopup = 'ol.products.list.items.product-items div.lightbulb-form-container button[title="Add to Cart"]';
const addToProjectToggleButton = 'button.action.toggle.change';
const createNewProjectLink = 'span[title="Create New Project"]';
const projectName = 'input#wishlist-name';
const createNewProjectButton = 'form#create-wishlist-form button[title="Save"]';
const addToQuote = 'button[class="action requisition-list-button toggle change"] span';
const quoteName = 'li.item > span.item-name';
const updateCart = 'button#product-updatecart-button';
const selectedProductImage = 'div.owl-stage div.variation-item.active';
const addNewQuote = '.item-action > .action > span';
const updateProjectLink = 'button[data-action="add-to-wishlist"] > span';
const updateQuoteLink = 'button[title="Update Quote"] > span';
const requestToOrderBtn = 'button#product-addtorequest-button';
const viewRequestOrderBtn = 'button#view-request-order-id';
const shipByDate = 'div.product-options-bottom div.product-info-stock-sku > div[title="Availability"] > span';
const designerName = 'div.product.attribute.brand > div[itemprop="brand"]';
const inchSpecification = 'a#spec-inch-tab-switch';
const cmSpecification = 'a#spec-cm-tab-switch';
const inchSpecificationData = 'div#spec-inch-tab > table > tbody > tr';
const cmSpecificationData = 'div#spec-cm-tab > table > tbody > tr';
const olapicSectionImages = 'div.olapic.olapic-container > div.images li > a';
const olapicSectionHeader = 'div.olapic.olapic-container > div.images > div.tag';
const moreFromSeriesHeader = 'div#additional-collection div.related-products-title > strong';
const moreFromSeriesProductName = 'div#additional-collection .item > .product-item-info > .details > .name';
const moreFromSeriesProductImage = 'div#additional-collection .photo > .product-image-container > .product-image-wrapper > img[src]:not([src=""])';
const moreFromSeriesProductDesignerName = 'div#additional-collection .product-item-info > .details > .designer_name';
const moreFromSeriesProductPrice = 'div#additional-collection div[class="product details product-item-details"] > div > span.normal-price';
const moreFromSeriesProductOriginalPrice = 'div#additional-collection div[class="product details product-item-details"] > div > span.old-price.sly-old-price.no-display';
const moreFromSeriesFirstProductName = 'div#additional-collection div#slick-slide00 li.item.product.product-item div.product.details.product-item-details > strong.product.name.product-item-name > a';
const viewSeriesLink = 'div.collection-link > a';

class ProductDescriptionPage {

    getPDPTitle () {
        cy.waitForSometime();
        return cy.title();
    }

    getPDPUrl () {
        return cy.url();
    }

    selectVariant (field, value) {
        cy.get('label:contains("' + field + '") + .control select').select(value);
    }

    clickAddToCart () {
        cy.wait(5000); //added static wait because of js load issue
        cy.get(addToCart).click();
    }

    getProductAddedMsg () {
        return cy.get(successMessage);
    }

    openProductDescriptionPageUrl (value) {
        cy.getBaseUrl().then((baseUrl) => {
            const pdpUrl = baseUrl + value;
            cy.visit(pdpUrl);
            cy.waitForSometime();
        });
    }

    getProductName () {
        return cy.get(productName).should('be.visible').then((displayedProductName) => {
            return displayedProductName.text().trim().toLowerCase();
        });
    }

    getProductSalePrice () {
        return cy.get(productSalePrice).should('be.visible').then((displayedSalePrice) => {
            return displayedSalePrice.text().trim();
        });
    }

    getProductOriginalPrice () {
        return cy.get(productOriginalPrice).then((displayedOriginalPrice) => {
            return displayedOriginalPrice.text().trim();
        });
    }

    clickOnSaveToProject () {
        cy.get(saveToProductLink).click();
    }

    getProductQty () {
        cy.wait(5000); //added static wait because of js load issue
        return cy.get(productQty).invoke('val');
    }

    getBulbQty () {
        return cy.get(bulbQty).then((displayedBulbQty) => {
            return displayedBulbQty.text().trim().replace('Qty: ', '');
        });
    }

    getBulbPrice () {
        return cy.get(bulbPrice).then((displayedBulbPrice) => {
            return displayedBulbPrice.text().trim();
        });
    }

    getSelectedDropdownOption (field) {
        return cy.get('label:contains("' + field + '") + .control select').invoke('find', ':selected')
        .then((selectedOption) => {
            return selectedOption.text().trim().toLowerCase();
        });
    }

    getSkuCode () {
        cy.scrollTo('top');
        return cy.get(skuCode).filter(':visible').should('be.visible').then((displayedSkuCode) => {
            return displayedSkuCode.text().trim();
        });
    }

    unselectBulbCheckbox () {
        cy.get(bulbCheckbox).click({ force: true });
    }

    getBulbName () {
        return cy.get(addBulbPopup).find(bulbName).then((displayedBulbName) => {
            return displayedBulbName.text().trim().toLowerCase();
        });
    }

    closeAddBulbPopup () {
        cy.get(addBulbPopup).find(addBulbPopupCloseButton).click({ force: true });
    }

    getProductAddedMsgText () {
        return this.getProductAddedMsg().should('be.visible').then((displayedSuccessMsg) => {
            return displayedSuccessMsg.text().trim().toLowerCase();
        });
    }

    getSaveToProjectOrQuoteSuccessMessage () {
        cy.scrollTo('top');
        return cy.get(saveToProjectOrQuoteSuccessMessage).should('be.visible').then((displayedMessage) => {
            return displayedMessage.text().trim().toLowerCase();
        });
    }

    clickAddToCartFromAddBulbPopup () {
        cy.get(addToCartFromAddBulbPopup).click();
    }

    clickAddToProjectToggleButton () {
        cy.get(addToProjectToggleButton).click();
    }

    clickCreateNewProjectLink () {
        cy.get(createNewProjectLink).click({ force: true });
    }

    enterProjectName (name) {
        cy.get(projectName).type(name);
    }

    clickCreateNewProjectButton () {
        cy.get(createNewProjectButton).click();
    }

    enterProductQuantity (quantity) {
        cy.get(productQty).should('be.visible').clear().type(quantity);
    }

    clickOnAddToQuote () {
        cy.waitForSometime();
        cy.wait(5000); //added static wait because of js load issue
        cy.get(addToQuote).click();
    }

    selectQuoteFromAddToQuote (quoteNameValue) {
        cy.contains(quoteName, quoteNameValue, { matchCase: false }).click({ force: true });
    }

    clickOnProductImage (imageName) {
        cy.wait(5000);
        cy.get('a[data-product-sku="' + imageName + '"]').click();
        cy.wait(5000);
    }

    clickUpdateCart () {
        cy.wait(5000); //added static wait because of js load issue
        cy.get(updateCart).click();
    }

    waitTillAddBulbPopupDisappear () {
        cy.get(addBulbPopup).should('not.exist');
    }

    getUpdateCartButton () {
        return cy.get(updateCart);
    }

    waitForProductImageDisplayAsSelected () {
        cy.get(selectedProductImage).scrollIntoView().should('be.visible');
    }

    clickOnAddNewQuote () {
        cy.get(addNewQuote).should('be.visible').click();
    }

    clickOnUpdateToProject () {
        cy.get(updateProjectLink).click();
    }

    clickOnUpdateQuote () {
        cy.get(updateQuoteLink).click();
    }

    clickOnRequestOrderButton () {
        cy.wait(5000); //added static wait because of js load issue
        cy.get(requestToOrderBtn).click();
    }

    clickOnViewYourRequestOrder () {
        cy.get(viewRequestOrderBtn).click();
    }

    validateShipByDateVisibility () {
        this.getShipByDate().then((displayedShipByDate) => {
            cy.log('Ship By Date: ' + displayedShipByDate);
        });
    }

    getShipByDate () {
        cy.get(shipByDate).should('be.visible').should('include.text', 'ship').should('include.text', 'by').as('shipByDate');

        return cy.get('@shipByDate').then((displayedShipByDateText) => {
            const shipDate = displayedShipByDateText.text().trim().match(/(\d{2}\.\d{2}\.\d{2})/);

            if (shipDate && shipDate.length > 0) {
                return shipDate[0];
            } else {
                throw new Error('Ship by date is not found.');
            }
        });
    }

    getDesignerName () {
        return cy.get(designerName).should('be.visible').then((displayedDesignerName) => {
            return displayedDesignerName.text().trim();
        });
    }

    clickOnInchSpecification () {
        cy.get(inchSpecification).should('be.visible').click();
    }

    clickOnCmSpecification () {
        cy.get(cmSpecification).should('be.visible').click();
    }

    getInchSpecDetails () {
        cy.get(inchSpecificationData).should('be.visible');
        return cy.getListElement(inchSpecificationData, '');
    }

    getCmSpecDetails () {
        cy.get(cmSpecificationData).should('be.visible');
        return cy.getListElement(cmSpecificationData, '');
    }

    getOlapicSectionImages () {
        cy.waitForSometime();
        return cy.get(olapicSectionImages);
    }

    getOlapicSectionHeader () {
        cy.waitForSometime();
        return cy.get(olapicSectionHeader).scrollIntoView();
    }

    getRequestOrderButton () {
        return cy.get(requestToOrderBtn).scrollIntoView();
    }

    clickOnViewSeries () {
        cy.get(viewSeriesLink).should('be.visible').click();
    }

    getMoreFromSeriesSectionHeader () {
        return cy.get(moreFromSeriesHeader).should('be.visible').scrollIntoView().then((displayedHeader) => {
            return displayedHeader.text().trim();
        });
    }

    getMoreFromSeriesProductImage () {
        return cy.get(moreFromSeriesProductImage);
    }

    getMoreFromSeriesProductName () {
        return cy.get(moreFromSeriesProductName);
    }

    getMoreFromSeriesProductDesignerName () {
        return cy.get(moreFromSeriesProductDesignerName);
    }

    getMoreFromSeriesProductPrice () {
        return cy.get(moreFromSeriesProductPrice);
    }

    clickOnMoreFromSeriesFirstProductName () {
        cy.get(moreFromSeriesFirstProductName).click({force: true});
    }

    getMoreFromSeriesFirstProductName () {
        return cy.get(moreFromSeriesFirstProductName).then((displayedProductName) => {
            return displayedProductName.text().trim();
        });
    }

    getMoreFromSeriesProductOriginalPrice () {
        return cy.get(moreFromSeriesProductOriginalPrice);
    }
    
} export default ProductDescriptionPage