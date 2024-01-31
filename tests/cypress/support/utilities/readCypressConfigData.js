//return the testing environment
Cypress.Commands.add('getTestingEnvironment', () => {
  const testingEnvironment = Cypress.env('testingEnvironment');
  const validEnvironments = ['production', 'staging', 'staging2', 'staging3', 'staging4', 'staging5', 'staging6', 'staging7', 'staging8', 'staging9'];
      
  if (validEnvironments.includes(testingEnvironment.toLowerCase())) {
    return testingEnvironment.toLowerCase();
  } else {
    throw new Error(`The provided '${testingEnvironment}' testing environment is not valid. Valid options are ${validEnvironments.join(', ')}.`);
  }
});
  
//return the base url 
Cypress.Commands.add('getBaseUrl', () => {
  cy.getTestingEnvironment().then((testingEnvironment) => {
    cy.getCountry().then((testingCountry) => {

      switch (testingCountry) {
        case 'uk':
          return Cypress.env(testingEnvironment.toLowerCase()).link + '/uk';
        case 'us':
          return Cypress.env(testingEnvironment.toLowerCase()).link;
        case 'eu':
          return Cypress.env(testingEnvironment.toLowerCase()).link + '/eu';
      }
    });
  });
});
  
//return the login credentials of specified user
Cypress.Commands.add('getLoginCredentials', (user) => {
  const validUsers = ['Retail', 'Trade', 'Wholesale'];
  let email, password;

  cy.getCountry().then((testingCountry) => { 
    switch (user.toLowerCase()) {
      case 'retail':
        email = Cypress.env(testingCountry.toUpperCase()).retailUserEmail;
        password = Cypress.env(testingCountry.toUpperCase()).retailUserPassword;
        break;
      case 'trade':
        email = Cypress.env(testingCountry.toUpperCase()).tradeUserEmail;
        password = Cypress.env(testingCountry.toUpperCase()).tradeUserPassword;
        break;
      case 'wholesale':
        email = Cypress.env(testingCountry.toUpperCase()).wholesaleUserEmail;
        password = Cypress.env(testingCountry.toUpperCase()).wholesaleUserPassword;
        break;
      default:
        throw new Error(`The provided '${user}' user type is not valid. Valid options are ${validUsers.join(', ')}.`);
    }
            
    return {email: email, password: password};
  });
});

//return the country
Cypress.Commands.add('getCountry', () => {
  const testingCountry = Cypress.env('country');
  const validCountry = ['UK', 'US', 'EU'];
      
  if (validCountry.includes(testingCountry.toUpperCase())) {
    return testingCountry.toLowerCase();
  } else {
    throw new Error(`The provided '${testingCountry}' testing country is not valid. Valid options are ${validCountry.join(', ')}.`);
  }
});