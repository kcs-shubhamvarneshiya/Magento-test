const { When, Then, Given } = require ('@badeball/cypress-cucumber-preprocessor');

import LoginPage from '../pageObjects/loginPage';
import HeaderPage from '../pageObjects/headerPage';

const login = new LoginPage();
const header = new HeaderPage();

Given('{string} user do login into the website with session', (user) => {
    //do login
    login.performLoginWithSession(user);

    //validate whether it gets login or not
    header.getUserIcon().should('be.visible');
});

When('user click on the login icon', () => {
    //click on the login icon
    header.clickLoginIcon();

    //validate login page title
    login.validateLoginPageTitle();
});

When('{string} user do login', (user) => {
    //do login
    login.performLogin(user);
});

Then('user should get logged-in', () => {
    //validate the account icon to check whether it get login or not
    header.getUserIcon().should('be.visible');
});