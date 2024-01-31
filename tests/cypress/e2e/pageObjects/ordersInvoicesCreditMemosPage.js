const searchBox = 'form#search-filter > input[name="search"]';
const selectedHeader = 'div#block-collapsible-nav > ul.nav.items > li.nav.item.current > strong';
const pagination = 'div.pages > ul.items.pages-items';
const paginationDropdown = 'select#limiter';
const paginationDropdownOptions = 'select#limiter > option';
const listHeaders = 'table#order-list th[scope="col"]';
const action = 'td[data-th="Action"]';
const viewLink = 'a.action.view.action-menu-item';
const numbersList = 'table#order-list td.col.id';
const pageTitle = 'h1.page-title > span[data-ui-id="page-title-wrapper"]';
const topHeaders = 'div#block-collapsible-nav > ul > li';
const filterBy = 'div#click-section span';
const filterLabel = 'ul > li > label#filter-label';
const noOrdersMessage = 'div#order-list-main > div:nth-child(1) > span';
const ordersList = 'div#order-list-main table#order-list';

class OrdersInvoicesCreditMemosPage {

    clickHeader (headerName) {
        cy.get(topHeaders).contains(headerName, {matchCase: false}).click();
    }

    getSearchBoxPlaceholder () {
        return cy.get(searchBox).invoke('attr', 'placeholder').then((placeholder) => {
            return placeholder.trim().toLowerCase();
        });
    }

    getSelectedHeader () {
        return cy.get(selectedHeader).then((headerName) => {
            return headerName.text().trim().toLowerCase();
        });
    } 

    getPagination () {
        return cy.get(pagination).scrollIntoView();
    }

    getSelectedPaginationDropdownOption () {
        cy.get(paginationDropdown).scrollIntoView();
        return cy.get(paginationDropdown).invoke('find', ':selected').then((selectedOption) => {
            return selectedOption.text().trim().toLowerCase();
        });
    }

    getPaginationDropdownOptions () {
        return cy.getListElement(paginationDropdownOptions, ",");    
    }

    getListHeaders () {
        return cy.getListElement(listHeaders, ",");    
    }

    clickViewLinkOfNumber (number) {
        cy.contains(numbersList, number).siblings(action).find(viewLink).click();
    }

    getPageTitle () {
        cy.waitForSometime();
        return cy.get(pageTitle);
    }

    getTopHeaders () {
        cy.waitForSometime();
        return cy.getListElement(topHeaders, ",");    
    }

    getSearchBox () {
        return cy.get(searchBox);
    }

    enterNumberInSearchBox (number) {
        cy.waitForSometime();
        cy.get(searchBox).should('be.visible').clear().type(number).type('{enter}');
    }

    getNumbersList () {
        return cy.get(numbersList);
    }

    clickFilterBy () {
        cy.get(filterBy).contains('Filter By', { matchCase: false }).should('be.visible').click();
    }

    getFilter (filterName) {
        return cy.get(filterLabel).contains(filterName);
    }

    getNoOrdersMessage () {
        return cy.get(noOrdersMessage).then((displayedMessage) => {
            return displayedMessage.text().trim();
        });
    }

    getOrdersList () {
        return cy.get(ordersList);
    }

} export default OrdersInvoicesCreditMemosPage