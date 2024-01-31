const projectGridLoader = 'div.admin__data-grid-loading-mask';
const projectListRow = 'table.data-grid.table > tbody > tr';
const yesDeleteProjectBtn = 'button.action-primary.action-accept';
const projectName = 'td[data-th="Project Name"] span';
const deleteProjectLink = 'td[data-th="Action"] li > a[data-action="item-delete"]';
const successMsg = 'div [class="page messages"] div[class="messages"]';
const projectDeletedMsg = 'Selected projects has been deleted.';
const projectNameList = 'table.data-grid.table > tbody > tr > td[data-th="Project Name"] a[title="Rename Project"] > span';
const createNewProjectButton = 'a#wishlist-create-button > span';
const projectNameTextBox = 'input#wishlist-name';
const createNewProject = 'button[title="Save"]';
const projectCheckbox = 'td.data-grid-checkbox-cell > label.data-grid-checkbox-cell-inner';
const deleteSelectedButton = 'button.btn.btn-secondary.action-menu-item.wishlist-delete';
const projectDetailsPageHeader = 'form#wishlist-view-form div.wishlist-title > strong';
const viewProjectLink = 'td[data-th="Action"] li > a[data-action="item-view"]';
const projectRenameTextBox = 'form#edit-wishlist-form input[name="name"]';
const saveProjectPopupBtn = 'button.btn.btn-primary.rename-popup > span';
const topHeaders = 'div#block-collapsible-nav > ul > li';
const paginationDropdown = 'select[data-role="limiter"]';
const itemCount = 'span.toolbar-number > span';
const productRemoveLink = 'div.itemaction > a[title="Remove"] > span';
const emptyList = 'div.message.info.empty > span:nth-child(1)';
const emptyMsg = 'You have no items in your project.';
const addToCart = 'button[title="Add to Cart"]';
const productEditLink = 'div.itemaction > a.action.edit > span';
const finish = 'div[title="Finish"] > span';
const productQty = 'input[data-role="qty"]';
const newProjectNameTextBox = 'form#create-wishlist-form input[name="name"]';
const productShipByDate = 'div[title="Availability"] > span';

class ProjectsPage {

    waitForProjectListLoad () {
        cy.get(projectGridLoader).should('not.be.visible');
    }

    clickDeleteProjectLink (projectNameValue) {
        cy.contains(projectName, projectNameValue).parents(projectListRow).find(deleteProjectLink).click();
    }

    clickYesDeleteProjectButton () {
        cy.get(yesDeleteProjectBtn).click();
    }

    getSuccessMsg () {
        return cy.get(successMsg);
    }

    deleteSpecificProject (projectNameValue) {
        let list = [];

        this.waitForProjectListLoad();

        cy.get(projectNameList).then((nameList) => {
            list = nameList.text().trim().toLowerCase();

            if (list.includes(projectNameValue.toLowerCase())) {
                this.clickDeleteProjectLink(projectNameValue);
                this.clickYesDeleteProjectButton();
                this.getSuccessMsg().should('be.visible').then((msg) => {
                    expect(msg.text().trim().toLowerCase()).to.be.equal(projectDeletedMsg.toLowerCase());
                });
            } else {
                cy.log('The provided ' + projectNameValue + ' project name is not found.');
            }
        });
    }

    getProjectsPageTitle () {
        cy.waitForSometime();
        return cy.title();
    }

    clickCreateNewProjectButton () {
        cy.get(createNewProjectButton).click();
    }

    enterProjectName (name) {
        cy.get(projectNameTextBox).clear().type(name);
    }

    clickCreateNewProject () {
        cy.get(createNewProject).click();
    }

    addNewProject (projectNameValue) {
        let list = [];

        this.waitForProjectListLoad();

        cy.get(projectNameList).then((nameList) => {
            list = nameList.text().trim().toLowerCase();

            if (list.includes(projectNameValue.toLowerCase())) {
                cy.log('The provided ' + projectNameValue + ' project name is found.');
            } else {
                this.clickCreateNewProjectButton();
                this.enterProjectName(projectNameValue);
                this.clickCreateNewProject();
                this.getSuccessMsg().should('contain.text', 'Project \"' + projectNameValue + '\" was saved.');
                cy.go('back');
            }
        });
    }

    getProjectNameList () {
        return cy.get(projectNameList);
    }

    clickProjectCheckbox (projectNameValue) {
        this.waitForProjectListLoad();
        cy.contains(projectName, projectNameValue).parents(projectListRow).find(projectCheckbox).click();
    }

    clickDeleteSelectedButton () {
        cy.contains(deleteSelectedButton, 'Delete Selected', {matchCase: false}).click();
    }

    getProjectDetailsPageHeader () {
        return cy.get(projectDetailsPageHeader);
    }

    clickViewProjectLink (projectNameValue) {
        cy.contains(projectName, projectNameValue).parents(projectListRow).find(viewProjectLink).click();
    }

    clickProjectName (projectNameValue) {
        this.waitForProjectListLoad();
        cy.contains(projectName, projectNameValue).click();
    }

    enterProjectRename (name) {
        cy.get(projectRenameTextBox).should('be.visible').clear().type(name);
    }

    clickSaveProjectPopupButton () {
        cy.get(saveProjectPopupBtn).click();
    }

    getTopHeaders () {
        cy.waitForSometime();
        return cy.getListElement(topHeaders, ",");    
    }

    getSelectedPaginationDropdownOption () {
        cy.get(paginationDropdown).scrollIntoView();
        return cy.get(paginationDropdown).invoke('find', ':selected').then((selectedOption) => {
            return selectedOption.text().trim().toLowerCase();
        });
    }

    getItemCount () {
        this.waitForProjectListLoad();
        cy.get(itemCount).scrollIntoView();
        return cy.get(itemCount).then((count) => {
            return count.text().replace('Item(s)', '').trim();
        });   
    }

    selectPaginationDropdownOption (option) {
        cy.get(paginationDropdown).select(option);
        this.waitForProjectListLoad();
    }

    clickRemoveLinkOfProduct () {
        cy.waitForSometime();
        cy.wait(5000); //added static wait because of js load issue
        cy.get(productRemoveLink).should('be.visible').click();
    }

    getEmptyListMsg () {
        return cy.get(emptyList).should('have.text', emptyMsg);  
    }

    clickAddToCart () {
        cy.waitForSometime();
        cy.get(addToCart).should('be.visible').click();
    }

    clickEditLinkOfProduct () {
        cy.waitForSometime();
        cy.get(productEditLink).should('be.visible').click();
    }

    getProductFinish () {
        return cy.get(finish).then((displayedProductFinish) => {
            return displayedProductFinish.text().toLowerCase().replace('finish:','').trim();
        });
    }

    getProductQty () {
        return cy.get(productQty).invoke('attr', 'value');
    }

    getNoItemsMsg () {
        return cy.get(emptyList);  
    }    

    enterNewProjectName (name) {
        cy.get(newProjectNameTextBox).should('be.visible').clear().type(name);
    }

    getProductShipByDate () {
        cy.get(productShipByDate).should('be.visible').should('include.text', 'ship').should('include.text', 'by').as('productShipByDate');

        return cy.get('@productShipByDate').then((displayedShipByDateText) => {
            const shipByDate = displayedShipByDateText.text().trim().match(/(\d{2}\.\d{2}\.\d{2})/);
            return shipByDate[0];
        });
    }

} export default ProjectsPage