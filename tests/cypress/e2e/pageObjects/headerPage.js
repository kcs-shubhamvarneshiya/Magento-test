const login = 'li.link.authorization-link a';
const loginLabel = 'Login';
const user = 'header.page-header li.customer-welcome';
const search = 'input#search';
const searchOptions = 'a.blm-autosuggest__suggestion-term-link';
const megaMenu = 'ul.vsmenu-list > li > a';
const submenuRoot = 'div.mega-menu.fullmenu';
const submenu = 'div.title.cat-name > a';
const miniCart = '[class="action showcart"]';
const beginCheckout = '[class="action primary checkout"]';
const miniCartLoader = '[class="loading-mask"]';
const miniCartProductDetails = '[class="product-item-details"]';
const profileIcon = 'button.action.switch';
const dropDownOptionsPageTitle = '[class="page-title"] span';
const logoutMessage = '[class="base"]';
const allDropDownOptions = '[class="customer-welcome active"] ul[class="header links"] a';
const miniCartItemNameList = 'li[data-role="product-item"] strong[class="product-item-name"]';
const miniCartItemDetails = 'li[data-role="product-item"]';
const miniCartItemPrice = 'span.minicart-price > span.price';
const miniCartItemQty = 'div.qty-edit input.item-qty.cart-item-qty';
const miniCartItemSeeDetailsLink = 'div.product.options > span.toggle';
const miniCartItemVariant = 'span[data-bind="text: option.value"]';
const miniCartCount = '[class="counter-number"]';
const miniCartViewCartButton = 'a.action.viewcart.btn.btn-secondary > span';
const noItemsInMiniCartMessage = '.subtitle.empty';
const miniCartItemImage = 'a.product-item-photo img.product-image-photo';
const miniCartSubtotal = 'div#minicart-content-wrapper div.subtotal span.price';
const miniCartItemRemoveLink = 'a.action.delete > span';
const miniCartItemEditLink = 'a[title="Edit item"] > span';
const miniCartUpdateQtyButton = 'div.qty-edit button[title="Update"]';
const miniCartItemCount = 'div#minicart-content-wrapper div.items-total > span.count';
const submenuHeader = 'div.sub-category > a';

class HeaderPage {

    clickLoginIcon () {
        cy.get(login).contains(loginLabel, { matchCase: false }).click({ force: true });
        cy.waitForSometime();
    }

    getUserIcon () {
        return cy.get(user);
    }

    getSearchTextbox () {
        return cy.get(search);
    }

    enterSearchText (value) {
        cy.get(search).should('not.be.disabled').clear().type(value);
    }

    clickSearchOption (value) {
        cy.get(searchOptions).contains(value, { matchCase: false }).click({ force: true });
    }

    mouseoverMegaMenu (value) {
        cy.waitForSometime();
        cy.get(megaMenu).contains(value, { matchCase: false }).should('be.visible').trigger('mouseover');
    }

    clickSubmenu (megaMenuValue, submenuValue) {
        cy.get(megaMenu).contains(megaMenuValue, { matchCase: false }).next(submenuRoot).find(submenu)
        .contains(submenuValue, { matchCase: false }).click({ force: true });
    }

    mouseoverMiniCartIcon () {
        cy.waitForSometime();
        cy.get(miniCart).trigger('mouseover');
    }

    clickBeginCheckOut () {
        this.waitForMiniCartLoaderNotExist();
        this.mouseoverMiniCartIcon();
        this.waitForMiniCartProductExist();
        cy.get(beginCheckout).click();
    }

    waitForMiniCartLoaderNotExist () {
        cy.get(miniCartLoader).should('not.exist');
    }

    waitForMiniCartProductExist () {
        cy.get(miniCartProductDetails).should('exist');
    }

    clickProfileIcon () {
        cy.waitForSometime();
        cy.get(profileIcon).filter(':visible').click({ force: true });
    }

    clickProfileDropDownOption (option) {
        cy.contains('a', option, { matchCase: false }).click({ force: true });
    }

    getProfileDropDownOptionTitle () {
        return cy.get(dropDownOptionsPageTitle);
    }

    getLogoutSuccessMessage () {
        return cy.get(logoutMessage);
    }

    pressEnterKey () {
        cy.get(search).type('{enter}');
    }

    clickOnMegaMenu (value) {
        cy.get(megaMenu).contains(value, { matchCase: false }).should('be.visible').click({ force: true });
    }

    getProfileDropDownOptions () {
        cy.wait(10000);  //added static wait because of js load issue
        cy.get(allDropDownOptions).should('be.visible');
        return cy.getListElement(allDropDownOptions, ",");
    }

    getMiniCartItemNameList () {
        return cy.get(miniCartItemNameList).then((displayedItemNameList) => {
            return displayedItemNameList.text().trim().toLowerCase();
        });
    }

    getMiniCartItemPrice (itemName) {
        return cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemPrice)
        .then((displayedItemPrice) => {
            return displayedItemPrice.text().trim();
        });
    }

    getMiniCartItemQty (itemName) {
        return cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemQty)
        .invoke('attr', 'data-item-qty');
    }

    clickMiniCartItemSeeDetailsLink (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemSeeDetailsLink)
        .click({ force: true });
    }

    getMiniCartItemVariant (itemName, variant) {
        return cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).contains(variant, { matchCase: false })
        .next('dd').find(miniCartItemVariant).then((displayedItemVariant) => {
            return displayedItemVariant.text().trim().toLowerCase();
        });
    }

    gotoProfileOptionPage (profileOptionName) {
        this.clickProfileIcon();
        this.clickProfileDropDownOption(profileOptionName);
        this.getProfileDropDownOptionTitle().should('be.visible').then((title) => {
            expect(title.text().trim().toLowerCase()).to.be.equal(profileOptionName.toLowerCase());
        });
    }

    getMiniCartCount () {
        cy.wait(10000);  //added static wait because of js load issue
        return cy.get(miniCartCount).then((count) => {
            let totalCount = count.text().trim();
            return totalCount;
        });
    }

    clickMiniCartIcon () {
        this.waitForMiniCartLoaderNotExist();
        cy.get(miniCart).click();
    }

    clickMiniCartViewCartButton () {
        cy.get(miniCartViewCartButton).click();
    }

    clickMiniCartItemName (itemName) {
        cy.get(miniCartItemNameList).contains(itemName, { matchCase: false }).click();
    }

    getNoItemsInMiniCartMessage () {
        return cy.get(noItemsInMiniCartMessage).then((displayedMessage) => {
            return displayedMessage.text().trim();
        });
    }

    clickMiniCartItemImg (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemImage).click();
    }

    getMiniCartSubtotalAsNumber () {
        this.waitForMiniCartProductExist();
        return cy.get(miniCartSubtotal).then((displayedSubtotal) => {
            cy.getCountry().then((testingCountry) => {
                if (testingCountry === 'us') {
                    return cy.convertPriceToNumber(displayedSubtotal.text().trim(), '$');
                } else if (testingCountry === 'uk') {
                    return cy.convertPriceToNumber(displayedSubtotal.text().trim(), '£');
                } else if (testingCountry === 'eu') {
                    return cy.convertPriceToNumber(displayedSubtotal.text().trim(), '€');
                }
            });
        });
    }

    clickMiniCartItemRemoveLink (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemRemoveLink)
        .click();
    }

    getMiniCartRemovedItemName (itemName) {
        return cy.contains(miniCartItemNameList, itemName, { matchCase: false });
    }

    clickMiniCartItemEditLink (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemEditLink)
        .click();
        cy.waitForSometime();
    }

    enterMiniCartItemQty (itemName, quantity) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartItemQty).clear().type(quantity);
    }

    clickMiniCartUpdateQtyButton (itemName) {
        cy.contains(itemName, { matchCase: false }).parents(miniCartItemDetails).find(miniCartUpdateQtyButton).as('updateButton');
        cy.get('@updateButton').should('be.visible').click();
        cy.wait(3000);
        cy.get('@updateButton').should('not.be.visible');
    }

    reloadCurrentPage () {
        cy.reload();
    }

    getMiniCartItemCountAsNumber () {
        return cy.get(miniCartItemCount).then((displayedItemCount) => {
            return cy.convertStringToNumber(displayedItemCount.text().trim());
        });
    }

    getSubmenuList (megaMenuValue) {
        return cy.get(megaMenu).contains(megaMenuValue, { matchCase: false }).next(submenuRoot).find(submenu).should('be.visible')
        .then((submenuList) => {
            return submenuList.text().trim();
        });
    }

    getSubmenuHeader (megaMenuValue) {
        return cy.get(megaMenu).contains(megaMenuValue, { matchCase: false }).next(submenuRoot).find(submenuHeader).should('be.visible')
        .then((submenuHeaderList) => {
            return submenuHeaderList.text().trim();
        });
    }

    getMegaMenu () {
        return cy.getListElement(megaMenu, ',');
    }

} export default HeaderPage
