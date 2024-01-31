const paginationDropdown = 'select#limiter';
const itemCount = 'span.toolbar-number';
const requestToOrderList = 'td[class="col id"]';
const requestToOrderNumber = 'td[data-th="Request To Order #"]';
const itemPrice = 'td[data-th="Price"]';
const itemSubTotal = 'td[class="col subtotal"] > span';
const productQuantity = 'td[data-th="Qty"] > input[name="qty"]';
const updateButton = 'button[class="action button update-qty"]';
const viewLink = 'td[class="col actions"] > a.action.view';
const skuCode = 'td[data-th="Attributes"] > p[class="ore-item-number"]';
const removeLink = 'p[class="mob-ore-remove-item"] > a';
const removeItemMessage = 'div.messages > div.message-success > div';
const productName = 'td[data-th="Attributes"] > p.mob-prod-name';
const productDetails = 'tr.item-info';
const loader = 'div.loader > img[alt="Loading..."]';
const requestOrderName = 'td[class="col name"]';
const requestOrderEmail = 'td[class="col email"]';
const requestOrderCreated = 'td[class="col created_at"]';
const requestOrderItemsCount = 'td[class="col count"]';
const requestOrderStatus = 'td[class="col status"]';
const addedProductsList = 'tbody.request.item > tr[class="item-info"]';
const productShipByDate = 'p.mob-p-available > span.mob-ore-availability';

class RequestToOrderPage {

    getSelectedPaginationDropdownOption () {
        return cy.get(paginationDropdown).invoke('find', ':selected').then((selectedOption) => {
            return selectedOption.text().trim().toLowerCase();
        });
    }

    selectPaginationDropdownOption (option) {
        cy.get(paginationDropdown).select(option);
    }

    getItemCount () {
        return cy.get(itemCount).then((count) => {
            return count.text().replace('Item', '').trim();
        });
    }

    getRequestToOrderList () {
        return cy.get(requestToOrderList);
    }

    clickOnViewLink (requestNumberValue) {
        cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(viewLink).click();
    }

    enterProductQuantity (productNameValue, quantity) {
        cy.wait(5000); //added static wait because of js load issue
        cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(productQuantity)
        .clear().type(quantity);
    }

    clickOnUpdateButton () {
        cy.get(updateButton).click();
    }

    getItemPrice (productNameValue) {
        return cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(itemPrice)
        .then((displayedItemPrice) => {
            const numericString = displayedItemPrice.text().trim().replace(/[^0-9.]/g, "");
            return parseFloat(numericString);
        });
    }

    getSubTotal (productNameValue) {
        this.waitForLoader();
        cy.wait(5000); //added static wait because of js load issue
        return cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(itemSubTotal)
        .then((displayedItemSubtotal) => {
            const numericString = displayedItemSubtotal.text().trim().replace(/[^0-9.]/g, "");
            return parseFloat(numericString);
        });
    }

    getProductPrice (productNameValue) {
        return cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(itemPrice)
        .then((displayedItemPrice) => {
            return displayedItemPrice.text().trim();
        });
    }

    getSkuCode (productNameValue) {
        return cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(skuCode)
        .then((displayedItemSKU) => {
            return displayedItemSKU.text().replace("Item # :", "").trim().toLowerCase();
        });
    }

    getProductQty (productNameValue) {
        return cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(productQuantity)
        .invoke('attr', 'value');
    }

    clickOnRemoveLink (productNameValue) {
        cy.wait(5000); //added static wait because of js load issue
        cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(removeLink).click();
    }

    getItemRemoveMessage () {
        return cy.get(removeItemMessage).then((displayedMessage) => {
            return displayedMessage.text().trim();
        });
    }

    waitForLoader () {
        cy.get(loader).should('not.be.visible');
    }

    getRequestOrderNo (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false });
    }

    getRequestName (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(requestOrderName);
    }

    getRequestEmail (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(requestOrderEmail);
    }

    getRequestCreatedAt (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(requestOrderCreated);
    }

    getRequestItemCount (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(requestOrderItemsCount);
    }

    getRequestStatus (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(requestOrderStatus);
    }

    getRequestViewLink (requestNumberValue) {
        return cy.contains(requestToOrderNumber, requestNumberValue, { matchCase: false }).parent('tr').find(viewLink);
    }

    getAddedProductsList () {
        return cy.get(addedProductsList);
    }

    getProductShipByDate (productNameValue) {
        cy.contains(productName, productNameValue, { matchCase: false }).parents(productDetails).find(productShipByDate)
        .should('be.visible').should('include.text', 'ship').should('include.text', 'by').as('productShipByDate');

        return cy.get('@productShipByDate').then((displayedShipByDateText) => {
            const shipByDate = displayedShipByDateText.text().trim().match(/(\d{2}\.\d{2}\.\d{2})/);
            return shipByDate[0];
        });
    }

} export default RequestToOrderPage