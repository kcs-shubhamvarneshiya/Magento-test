const itemNameList = 'div.product-item-details > strong.product-item-name';
const itemDetails = 'tbody.cart.item';
const itemPrice = 'td[data-th="Price"] span.cart-price > span.price';
const itemQty = 'td[data-th="Qty"] > div.field.qty input[data-role="cart-item-qty"]';
const itemVariant = 'dd';
const clearCartButton = 'button#empty_cart_button';
const clearCartPopupOkButton = '.action-primary.action-accept';
const orderSubtotal = 'div.cart-summary > div#cart-totals table.data.table.totals tr.totals.sub > td.amount > span.price';
const cartEmpty = 'div.cart-empty';
const itemSku = 'div.product-item-details > div.product-item-sku';
const cartPageTitle = 'Shopping Cart';
const clearCartPopupCancelButton = '.action-secondary.action-dismiss';
const itemRemoveLink = 'a[title="Remove"] > span';
const itemImage = 'td[data-th="Item"] > a img.product-image-photo';
const itemEditLink = 'a[title="Edit item parameters"] > span';
const successMsg = 'div.page.messages div[data-ui-id="message-success"] > div';
const itemNameLink = 'td[data-th="Item"] > div.product-item-details > strong.product-item-name > a';
const itemSubtotal = 'td.col.subtotal span.price';
const orderTax = 'div#cart-totals tr.totals-tax > td.amount > span.price';
const merchandiseTotal = 'div#cart-totals tr.grand.totals > td[data-th="Merchandise Total"] span.price';
const loader = 'div.loader > img';
const addItemButtonOfRequisitionListPopup = '.modal-footer > button.action.primary.add > span';
const itemAddToQuoteLink = 'Add to Quote';
const itemMoveToProjectLink = 'Move to Project';
const clearCartPopupXIconButton = '.modal-popup.confirm._show > div > header > .action-close';
const shippingCharge = 'div#cart-totals tr.totals.shipping.excl > td.amount > span.price';
const vatCharge = 'div#cart-totals tr.totals-tax > td.amount > span.price';
const addToProjectToggleButton = 'button.action.toggle.change';
const quoteName = 'li.item > span.item-name';
const itemShipByDate = 'div.product-item-details > div.product-stock';
const beginCheckoutButton = 'div.cart-summary button[title="Begin Checkout"]';

class ShoppingCartPage {

    getShoppingCartPageTitle () {
        return cy.title();
    }

    getItemNameList () {
        return cy.get(itemNameList).then((displayedItemNameList) => {
            return displayedItemNameList.text().trim().toLowerCase();
        });
    }

    getItemPrice (itemName) {
        return cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemPrice)
        .then((displayedItemPrice) => {
            return displayedItemPrice.text().trim();
        });
    }

    getItemQty (itemName) {
        return cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemQty)
        .invoke('attr', 'value');
    }

    getItemVariant (itemName, variant) {
        return cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails)
        .contains(variant, {matchCase: false}).next(itemVariant)
        .then((displayedItemVariant) => {
            return displayedItemVariant.text().trim().toLowerCase();
        });
    }

    getOrderSubtotal () {
       return cy.get(orderSubtotal);
    }

    clickOnClearCartButton () {
        this.getOrderSubtotal().should('be.visible');
        cy.get(clearCartButton).click();
    }
				
	clickOnClearCartPopupOkButton () {
        cy.get(clearCartPopupOkButton).click();
    }

    getCartEmptyMessage () {
        return cy.get(cartEmpty);
    }

    getShoppingCartPageUrl () {
        return cy.url();
    }

    getItemSKU (itemName) {
        return cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemSku)
        .then((displayedItemSku) => {
            return displayedItemSku.text().trim().replace('Item #:\n','').trim();
        });
    }

    validateShoppingCartPageTitle (){
        cy.title().should('contain', cartPageTitle);
    }

    clickOnClearCartPopupCancelButton () {
        cy.get(clearCartPopupCancelButton).click();
    }

    clickRemoveLinkOfItem (itemName) {
        cy.waitForSometime();
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemRemoveLink)
        .click();
    }

    clickOnItemImage (itemName) {
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemImage).click();
    }

    clickEditLinkOfItem (itemName) {
        cy.waitForSometime();
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemEditLink).click();
        cy.waitForSometime();
    }

    getSuccessMessage () {
        return cy.get(successMsg).then((displayedSuccessMessage) => {
            return displayedSuccessMessage.text().trim();
        });
    }

    clickOnItemName (itemName) {
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemNameLink).click();
    }

    enterItemQty (itemName, qty) {
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemQty).clear().type(qty + '{enter}');
    }

    getItemSubtotalAsNumber (itemName) {
        return cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemSubtotal).then((displayedItemSubtotal) => {
            cy.getCountry().then((testingCountry) => {    
                if (testingCountry === 'us') {
                    return cy.convertPriceToNumber(displayedItemSubtotal.text().trim(), '$');
                } else if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedItemSubtotal.text().trim(), '£');
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedItemSubtotal.text().trim(), '€');
                }
            });
        });
    }

    getSumOfAllItemSubtotal () {
        let allItemSubTotal = 0;

        return cy.get(itemSubtotal).then((subtotalList) => {
            subtotalList.each((index, itemSubtotal) => {
                cy.getCountry().then((testingCountry) => {
                    if (testingCountry === 'us') {
                        return cy.convertPriceToNumber(Cypress.$(itemSubtotal).text().trim(), '$').then((itemSubtotalAsNumber) => {
                            return allItemSubTotal += itemSubtotalAsNumber;
                        });
                    } else if (testingCountry === 'uk') {
                        return cy.convertPriceToNumber(Cypress.$(itemSubtotal).text().trim(), '£').then((itemSubtotalAsNumber) => {
                            return allItemSubTotal += itemSubtotalAsNumber;
                        });
                    } else if (testingCountry === 'eu') {
                        return cy.convertPriceToNumber(Cypress.$(itemSubtotal).text().trim(), '€').then((itemSubtotalAsNumber) => {
                            return allItemSubTotal += itemSubtotalAsNumber;
                        });
                    }
                });
            });
        });
    }

    getOrderSubtotalAsNumber () {
        return cy.get(orderSubtotal).then((displayedOrderedSubtotal) => {
            cy.getCountry().then((testingCountry) => {
                if (testingCountry === 'us') {
                    return cy.convertPriceToNumber(displayedOrderedSubtotal.text().trim(), '$');
                } else if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedOrderedSubtotal.text().trim(), '£');
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedOrderedSubtotal.text().trim(), '€');
                }   
            });
        });
    }

    getOrderTaxAsNumber () {
        cy.waitForOrderSummaryLoad();
        return cy.getCountry().then((testingCountry) => {
            if (testingCountry === 'us') {
                return cy.get(orderTax).should('not.have.text', '$0.00').then((displayedOrderedTax) => {
                    return cy.convertPriceToNumber(displayedOrderedTax.text().trim(), '$');
                });
            } else if (testingCountry === 'uk') {
                return cy.get(orderTax).should('not.have.text', '£0.00').then((displayedOrderedTax) => {
                    return cy.convertPriceToNumber(displayedOrderedTax.text().trim(), '£');
                });
            } else if (testingCountry === 'eu') {
                return cy.get(orderTax).should('not.have.text', '€0.00').then((displayedOrderedTax) => {
                    return cy.convertPriceToNumber(displayedOrderedTax.text().trim(), '€');
                });
            }
        }); 
    }

    getMerchandiseTotalAsNumber () {
        return cy.get(merchandiseTotal).should('be.visible').then((displayedMerchandiseTotal) => {
            cy.getCountry().then((testingCountry) => {
                if (testingCountry === 'us') {
                    return cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '$');
                } else if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '£');
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedMerchandiseTotal.text().trim(), '€');
                }   
            });
        });
    }

    waitForOrderSummaryLoad () {
        cy.get(loader).should('not.exist');
    }

    clickMoveToProjectLinkOfItem () {
        cy.waitForOrderSummaryLoad();
        cy.get(addToProjectToggleButton).should('be.visible');
        cy.contains(itemMoveToProjectLink, {matchCase: false}).click();
    }

    clickAddToQuoteLinkOfItem (itemName) {
        cy.waitForSometime();
        cy.contains(itemNameList, itemName, {matchCase: false}).should('be.visible').parents(itemDetails)
        .contains(itemAddToQuoteLink, {matchCase: false}).click();
    }

    clickAddItemButtonOfRequisitionListPopup () {
        cy.get(addItemButtonOfRequisitionListPopup).click();
    }

    clickOnClearCartPopupXIconButton () {
        cy.get(clearCartPopupXIconButton).click();
    }

    getShippingChargeAsNumber () {
        cy.wait(10000); //added static wait because of js load issue
        return cy.getCountry().then((testingCountry) => {
            if (testingCountry === 'us') {
                return cy.get(shippingCharge).should('be.visible').then((displayedShippingCharge) => {
                    return cy.convertPriceToNumber(displayedShippingCharge.text().trim(), '$');   
                });
            } else if (testingCountry === 'uk') {
                return cy.get(shippingCharge).should('be.visible').then((displayedShippingCharge) => {
                    return cy.convertPriceToNumber(displayedShippingCharge.text().trim(), '£');   
                });
            } else if (testingCountry === 'eu') {
                return cy.get(shippingCharge).should('be.visible').then((displayedShippingCharge) => {
                    return cy.convertPriceToNumber(displayedShippingCharge.text().trim(), '€');   
                });
            }
        }); 
    }

    getVATChargeAsNumber () {
        return cy.get(vatCharge).should('be.visible').then((displayedVATCharge) => {
            cy.getCountry().then((testingCountry) => {
                if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedVATCharge.text().trim(), '£');
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedVATCharge.text().trim(), '€');
                }   
            });
        });
    }

    selectQuoteFromAddToQuote (quoteNameValue) {
        cy.waitForOrderSummaryLoad();
        cy.contains(quoteName, quoteNameValue, {matchCase: false}).click({force: true});
    }

    getItemShipByDate (itemName) {
        cy.contains(itemNameList, itemName, {matchCase: false}).parents(itemDetails).find(itemShipByDate).should('be.visible')
        .should('include.text', 'ship').should('include.text', 'by').as('itemShipByDate');

        return cy.get('@itemShipByDate').then((displayedShipByDateText) => {
            const shipByDate = displayedShipByDateText.text().trim().match(/(\d{2}\.\d{2}\.\d{2})/);
            return shipByDate[0];
        });
    }

    clickBeginCheckoutButton () {
        this.getOrderSubtotal().should('be.visible');
        cy.waitForOrderSummaryLoad();
        cy.get(beginCheckoutButton).filter(':visible').should('be.visible').click({force: true});
    }

    getShippingCharge () { 
        cy.wait(10000); //added static wait because of js load issue
        return cy.get(shippingCharge).should('be.visible').then((displayedShippingCharge) => {
            return displayedShippingCharge.text().trim();
        });
    }

    getOrderTax () { 
        cy.waitForOrderSummaryLoad();
        return cy.get(orderTax).should('be.visible').then((displayedOrderTax) => {
            return displayedOrderTax.text().trim();
        });
    }

    getMerchandiseTotal () { 
        return cy.get(merchandiseTotal).should('be.visible').then((displayedMerchandiseTotal) => {
            return displayedMerchandiseTotal.text().trim();
        });
    }

    getOrderSubtotalAmt () {
        return this.getOrderSubtotal().should('be.visible').then((displayedOrderSubtotalAmt) => {
            return displayedOrderSubtotalAmt.text().trim();
        });
    }

} export default ShoppingCartPage