const { When, Then } = require('@badeball/cypress-cucumber-preprocessor');

import HeaderPage from '../pageObjects/headerPage';
import HomePage from '../pageObjects/homePage';

const home = new HomePage();
const header = new HeaderPage();

When('user click on profile icon', () => {
  //click on the profile icon
  header.clickProfileIcon();
});

When('user click on below option', (dataTable) => {
  cy.getCountry().then((testingCountry) => {
    for (let i = 1; i < dataTable.rawTable.length; i++) {
      const country = dataTable.rawTable[i][0].toLowerCase();
      const option = dataTable.rawTable[i][1].toString();

      if (testingCountry === country) {
        //click on the profile option
        header.clickProfileDropDownOption(option);
        break;
      }
    }
  });
});

Then('{string} page should get loaded', (profileOption) => {
  //validate whether the page header contains profile option or not
  header.getProfileDropDownOptionTitle().should('have.text', profileOption);
});

Then('user should be able to logout with following message', (dataTable) => {
  const logoutMessage = dataTable.rawTable[1];

  //validate the logout message
  header.getLogoutSuccessMessage().should('have.text', logoutMessage.toString());
  //wait till the logout message get disappeared
  header.getLogoutSuccessMessage().should('not.exist');
});

Then('user redirect to home page which has below title', (dataTable) => {
  cy.getCountry().then((testingCountry) => {
    for (let i = 1; i < dataTable.rawTable.length; i++) {
      const country = dataTable.rawTable[i][0].toLowerCase();
      const homePageTitle = dataTable.rawTable[i][1].toString().replace(/ /g, '');

      if (testingCountry === country) {
        //validate home page title
        home.getHomePageTitle().then((actualTitle) => {
          expect(actualTitle.toLowerCase().replace(/\|/g, '').replace(/ /g, '')).to.eq(homePageTitle.toLowerCase());
        });
        break;
      }
    }
  });
});

Then('the user should be able to see below available profile options', (dataTable) => {
  cy.getCountry().then((testingCountry) => {
    for (let i = 1; i < dataTable.rawTable.length; i++) {
      const country = dataTable.rawTable[i][0].toLowerCase();
      const expectedDropdownOptions = dataTable.rawTable[i][1].toString().replace(/ /g, '');

      if (testingCountry === country) {
        //validate available dropdown options
        header.getProfileDropDownOptions().then((actualDropdownOptions) => {
          expect(actualDropdownOptions.toLowerCase().replace(/ /g, '')).to.eq(expectedDropdownOptions.toLowerCase());
        });
        break;
      }
    }
  });
});