const listHeaders = 'th.data-grid-th > span';
const newProject = '#requisition-list-name';
const saveQuoteButton = '.modal-footer > .primary';
const deleteQuoteLink = 'button.action.delete > span';
const deleteQuotePopup = '._show > .modal-inner-wrap > .modal-footer > .primary';
const projectLabel = 'tr.data-row';
const viewAllLink = 'td[data-th="Actions"] a[data-action="item-view"]';
const projectNameList = 'table.data-grid.table > tbody > tr > td[data-th="Project Name"] div.cell-label-line-name';
const projectGridLoader = 'div.admin__data-grid-loading-mask';
const paginationDropdown = 'select[data-role="limiter"]';
const itemCount = 'span.toolbar-number > span';
const latestActivity = 'td[data-th="Latest Activity"] > .data-grid-cell-content';
const projectNameDetailPage = 'div.requisition-list-title > div.block-title';
const latestActivityDetailPage = 'div.requisition-list-title > ul.requisition-list > li:nth-child(1)';
const productEditLink = 'td[class="col subtotal action"] button[data-action="edit-item"] > span';
const productNameLabel = 'td[data-th="Product"] span.product-item-name';
const productQty = 'td[data-th="Qty"] > input[data-role="requisition-item-qty"]';
const productRemoveLink = 'td[class="col subtotal action"] button[data-action="remove-item"] > span';
const deleteButtonOfRemoveFromQuotePopup = 'button[class="action primary confirm"] > span';
const project = '.cell-label-line-name';
const createdBy = '[data-th="Created By"] > .data-grid-cell-content';
const quoteTotal = '[data-th="Quote Total"] > .data-grid-cell-content';
const createNewQuoteBtn = 'button[class="action add btn btn-secondary"]';

class QuotesPage {

    getListHeaders () {
        return cy.getListElement(listHeaders, ",").then((headers) => {
            return headers.toLowerCase().replace(',actions', '');
        });
    }

    enterProjectName (projectName) {
        cy.get(newProject).clear().type(projectName);
    }

    clickOnSaveButton () {
        cy.get(saveQuoteButton).click();
        cy.waitForSometime();
    }

    clickOnDeleteQuoteLink () {
        cy.waitForSometime();
        cy.scrollTo('top');
        cy.wait(5000); //added static wait because of js load issue
        cy.get(deleteQuoteLink).click();
    }

    clickViewLink (projectName) {
        cy.waitForSometime();
        this.waitForProjectListLoad();
        cy.contains(projectLabel, projectName, { matchCase: false }).find(viewAllLink).should('be.visible').click();
    }

    clickOnDeleteQuotePopup () {
        cy.get(deleteQuotePopup).click();
    }

    waitForProjectListLoad () {
        cy.get(projectGridLoader).should('not.be.visible');
    }

    deleteSpecificQuote (projectNameValue) {
        let list = [];

        this.waitForProjectListLoad();

        cy.get(projectNameList).then((nameList) => {
            list = nameList.text().trim().toLowerCase();

            if (list.includes(projectNameValue.toLowerCase())) {
                this.clickViewLink(projectNameValue);
                this.clickOnDeleteQuoteLink();
                this.clickOnDeleteQuotePopup();
                this.waitForProjectListLoad();

                cy.get(projectNameList).then($list => {
                    $list.each((index, $el) => {
                        const displayedName = Cypress.$($el).text().trim();
                        expect(displayedName.toLowerCase()).to.not.equal(projectNameValue.toLowerCase());
                    });
                });
            } else {
                cy.log('The provided ' + projectNameValue + ' project name is not found.');
            }
        });
    }

    selectPaginationDropdownOption (option) {
        cy.get(paginationDropdown).select(option);
        this.waitForProjectListLoad();
    }

    getItemCount () {
        this.waitForProjectListLoad();
        cy.get(itemCount).scrollIntoView();
        return cy.get(itemCount).then((count) => {
            return count.text().replace('Item(s)', '').trim();
        });
    }

    getProjectNameList () {
        return cy.get(projectNameList);
    }

    getLatestActivityDate (projectName) {
        this.waitForProjectListLoad();
        return cy.contains(projectLabel, projectName, { matchCase: false }).find(latestActivity).then((displayedLatestActivity) => {
            return displayedLatestActivity.text().trim();
        });
    }

    getProjectNameDetailPage () {
        cy.waitForSometime();
        return cy.get(projectNameDetailPage).then((displayedProjectName) => {
            return displayedProjectName.text().trim().toLowerCase();
        });
    }

    getLatestActivityDetailPage () {
        cy.waitForSometime();
        return cy.get(latestActivityDetailPage).then((displayedLatestActivity) => {
            const date = displayedLatestActivity.text().replace('Created\n', '').trim();
            const dateParts = date.split(' ');
            const month = dateParts[0].substring(0, 3)
            const day = dateParts[1].replace(',', '');
            const year = dateParts[2];
            const newDateFormat = `${month} ${day}, ${year}`;
            return newDateFormat.replace(/0/g, '');
        });
    }

    clickProductEditLink (productNameValue) {
        cy.waitForSometime();
        cy.wait(5000); //added static wait because of js load issue
        cy.contains(productNameLabel, productNameValue, { matchCase: false }).parents('tr').find(productEditLink).click();
    }

    getProductQty (productNameValue) {
        return cy.contains(productNameLabel, productNameValue, { matchCase: false }).parents('tr').find(productQty).invoke('attr', 'value');
    }

    clickProductRemoveLink (productNameValue) {
        cy.waitForSometime();
        cy.wait(5000); //added static wait because of js load issue
        cy.contains(productNameLabel, productNameValue, { matchCase: false }).parents('tr').find(productRemoveLink).click();
    }

    clickDeleteButtonOfRemoveFromQuotePopup () {
        cy.get(deleteButtonOfRemoveFromQuotePopup).click();
    }

    getRemovedProductName (productNameValue) {
        return cy.contains(productNameLabel, productNameValue, { matchCase: false });
    }

    getProject (projectName) {
        return cy.contains(projectLabel, projectName, { matchCase: false }).find(project);
    }

    getCreatedBy (projectName) {
        return cy.contains(projectLabel, projectName, { matchCase: false }).find(createdBy);
    }

    getQuoteTotal (projectName) {
        return cy.contains(projectLabel, projectName, { matchCase: false }).find(quoteTotal);
    }

    getViewLink (projectName) {
        return cy.contains(projectLabel, projectName, { matchCase: false }).find(viewAllLink);
    }

    clickCreateNewQuoteButton () {
        cy.waitForSometime();
        cy.get(createNewQuoteBtn).click();
    }

} export default QuotesPage