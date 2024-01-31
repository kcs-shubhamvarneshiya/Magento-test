const addNewUserBtn = 'button[title="Add New User"]';
const jobTitle = 'input#jobtitle';
const firstName = 'input#firstname';
const lastName = 'input#lastname';
const email = 'input#email';
const phoneNumber = 'input#telephone';
const saveBtn = 'button.btn.btn-primary.rename-popup';
const successMsg = 'div.messages > div[data-ui-id="message-success"] > div';
const loader = 'div.loader > img';
const emailList = 'td[data-th="Email"] > div';
const listLoader = 'div.admin__data-grid-loading-mask';
const nameList = 'td[data-th="Name"] > div';
const deleteUserLink ='td[data-th="Actions"] > a[data-action="item-delete"]';
const deleteBtn = 'button.action.primary.delete > span';
const deleteUserMsg = 'The customer was successfully deleted.';
const editUserLink ='td[data-th="Actions"] > a[data-action="item-edit"]';

class CompanyUsersPage {

    clickAddNewUserBtn () {
        cy.waitForSometime();
        cy.get(addNewUserBtn).should('be.visible').click();
    } 

    enterFirstName (value) {
        cy.get(firstName).should('be.visible').clear().type(value);
    }

    enterLastName (value) {
        cy.get(lastName).should('be.visible').clear().type(value);
    }

    enterEmail (value) {
        return cy.createUniqueEmail(value).then((emailAddress) => {
            return cy.get(email).should('be.visible').clear().type(emailAddress).then(() => {
                return emailAddress;
            });
        });
    }

    enterJobTitle (value) {
        cy.get(jobTitle).should('be.visible').clear().type(value);
    }

    enterPhoneNumber (value) {
        cy.get(phoneNumber).should('be.visible').clear().type(value);
    }

    clickSaveBtn () {
        cy.get(saveBtn).should('be.visible').click();
        cy.get(loader).should('not.be.visible');
        cy.get(saveBtn).should('not.be.visible');
    } 

    getSuccessMsg (msgText) {
        cy.scrollTo('top');
        return cy.contains(successMsg, msgText, { matchCase: false }).then((displayedMsg) => {
            return displayedMsg.text().trim();
        });
    }

    getEmailList () {
        cy.get(listLoader).should('not.be.visible');  
        return cy.getListElement(emailList, ',');
    }

    getNameList () {
        return cy.getListElement(nameList, ',');
    }

    deleteUser (userEmail) {
        this.clickDeleteLink(userEmail);
        this.clickDeleteBtn();
        cy.get(listLoader).should('not.be.visible');  
        this.getDeletedUser(userEmail).should('not.exist');
        this.getSuccessMsg(deleteUserMsg).then((msg) => {
            expect(msg).contain(deleteUserMsg);
        });
    }

    clickEditLink (userEmail) {
        cy.contains(emailList, userEmail, { matchCase : false }).parents('tr').find(editUserLink).click();
    } 

    clickDeleteLink (userEmail) {
        cy.contains(emailList, userEmail, { matchCase : false }).parents('tr').find(deleteUserLink).click();
    } 

    clickDeleteBtn () {
        cy.get(deleteBtn).should('be.visible').click();
    } 

    getDeletedUser (userEmail) {
        return cy.contains(emailList, userEmail, { matchCase : false });
    }

} export default CompanyUsersPage