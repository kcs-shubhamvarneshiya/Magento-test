const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import CompanyUsersPage from '../pageObjects/companyUsersPage';
const companyUsersPage = new CompanyUsersPage();

let newUserName, newUserEmail;

When('user click on the add new user button', () => {
    //click on the add new user button
    companyUsersPage.clickAddNewUserBtn();
});

When('user enter the following user details:', (dataTable) => {
    const validFields = ['Job Title', 'First Name', 'Last Name', 'Email', 'Phone Number'];
    newUserName = '';

    //enter the user details
    dataTable.hashes().forEach((row) => {
        const field = row.field.toLowerCase();
        const value = row.value;

        switch (field) {
            case 'job title':
                companyUsersPage.enterJobTitle(value);
                break;
            case 'first name':
                companyUsersPage.enterFirstName(value);
                newUserName = value;
                break;
            case 'last name':
                companyUsersPage.enterLastName(value);
                newUserName = newUserName + ' ' + value;
                break;
            case 'email':
                newUserEmail = '';
                companyUsersPage.enterEmail(value).then((email) => {
                    newUserEmail = email;
                });
                break;
            case 'phone number':
                companyUsersPage.enterPhoneNumber(value);
                break;
            default:
                throw new Error(`The provided '${field}' field is not valid. Valid options are ${validFields.join(', ')}.`);
        }     
    }); 
});

When('user click on the save button from the user popup', () => {
    //click on the save button
    companyUsersPage.clickSaveBtn();
});

Then('the following success message should be displayed on the company users page', (dataTable) => {
    const expectedSuccessMessage = dataTable.rawTable[1].toString();

    //validate the success message
    companyUsersPage.getSuccessMsg(expectedSuccessMessage).then((displayedMessage) => {
        expect(displayedMessage).to.contain(expectedSuccessMessage);
    });
});

Then('the given name and email should be displayed on the company users page', () => {
    //validate the email from the list
    companyUsersPage.getEmailList().then((emailList) => {
        expect(emailList).include(newUserEmail);
    });

    //validate the name from the list
    companyUsersPage.getNameList().then((nameList) => {
        expect(nameList).include(newUserName);
    });
});

When('user delete the given user', () => {
    //delete the recently added/updated user
    companyUsersPage.deleteUser(newUserEmail);
});

When('user click on the edit link of given user from the company users list page', () => {
    //click on the edit link
    companyUsersPage.clickEditLink(newUserEmail);
});

When('user click on the delete link of given user from the company users list page', () => {
    //click on the delete link
    companyUsersPage.clickDeleteLink(newUserEmail);
});

When('user click on the delete button from the delete user popup', () => {
    //click on the delete button
    companyUsersPage.clickDeleteBtn();
});

Then('the deleted user should not be displayed on the company users list page', () => {
    //validate the deleted user should not be displayed on the list
    companyUsersPage.getDeletedUser(newUserEmail).should('not.exist');
});