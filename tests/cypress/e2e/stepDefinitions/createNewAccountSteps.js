const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import CreateNewAccountPage from '../pageObjects/createNewAccountPage';
import HeaderPage from '../pageObjects/headerPage';

const signUp = new CreateNewAccountPage();
const header = new HeaderPage();

When('user click on the create an account button', () => {
    //click on the create an account button
    signUp.clickCreateAnAccountButton();

    //validate sign up page title
    signUp.validateSignUpPageTitle('Create New Customer Account');
});

When('user enter the following new retail account details:', (dataTable) => {
    //enter the account details
    signUp.enterRetailAccountDetails(dataTable);
});

When('user click on the create account button', () => {
    //click on the create account button
    signUp.clickCreateAccountButton();
});

Then('new retail account should get created with the following success message:', (dataTable) => {
    const successMessage = dataTable.rawTable[1];

    //validate the thank you msg
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') { 
            signUp.getThankYouForRegisteringMsg().should('have.text', successMessage.toString());
        }
    });
});

Then('welcome message should contain the following first and last name:', (dataTable) => {
    const firstName =  dataTable.rawTable[1][1]
    const lastName = dataTable.rawTable[2][1];

    //validate the first and last name of the welcome msg
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            signUp.getWelcomeMsg().should('contain.text', firstName).should('contain.text', lastName);
        }
    });
});

Then('user should get logged into the account', () => {
    //validate the account icon to check whether it get login or not
    cy.getTestingEnvironment().then((testingEnvironment) => {
        if (testingEnvironment.toLowerCase() !== 'production') {
            header.getUserIcon().should('be.visible');
        }
    });
});

When('user click on the request an account button', () => {
    //click on the request an account button
    signUp.clickRequestAccountButton();

    //validate sign up page title
    signUp.validateSignUpPageTitle('Request Trade/Wholesale Account');
});

When('user enter the following account details:', (dataTable) => {
    //enter the account details
    signUp.enterTradeWholesaleAccountDetails(dataTable);
});

When('user enter the following address details:', (dataTable) => {
    //enter the address details
    signUp.enterAddressDetails(dataTable);
});

When('user upload {string} file located in the fixtures folder', (proofFileName) => {
    //upload the given file
    signUp.uploadFile(proofFileName);
});

When('user click on the submit account request button', () => {
    //click on the submit account request button 
    signUp.clickSubmitAccountRequestButton();
});

Then('new wholesale or trade account should get created with the following success message', (dataTable) => {
    const successMessage = dataTable.rawTable[1].toString();

    //validate the success message
    signUp.getSuccessMsg().should('have.text', successMessage);
});