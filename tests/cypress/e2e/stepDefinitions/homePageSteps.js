const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import HomePage from '../pageObjects/homePage';
import HeaderPage from '../pageObjects/headerPage';

const home = new HomePage();
const header = new HeaderPage();

When('user opens the home page url', () => {
    //open home page url
    home.openHomePageUrl();
});

Then('home page should get opened with below title', (dataTable) => {   
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const title = dataTable.rawTable[i][1].toString().replace(/ /g, '');
                    
            if (testingCountry === country) {
                //validate home page title
                home.getHomePageTitle().then((actualTitle) => {
                   expect(actualTitle.toLowerCase().replace(/\|/g, '').replace(/ /g, '')).to.eq(title.toLowerCase());                                  
                });
            }
        }
    });
});

Then('user should be able to see the below country as default country', (dataTable) => {    
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();

            if (testingCountry === country && testingCountry === 'us') {
                //validate the US flag should be displayed
                home.getUSFlagIcon().should('be.visible');
            } else if (testingCountry === country && testingCountry === 'uk') {
                //validate the UK flag should be displayed
                home.getUKFlagIcon().should('be.visible');
            } else if (testingCountry === country && testingCountry === 'eu') {
                //validate the EU flag should be displayed
                home.getEUFlagIcon().should('be.visible');
            }
        }
    });
});

When('user click on the country flag', () => {
    //click on country flag
    home.clickCountryFlag();
});

Then('user should be able to see below available countries in dropdown', (dataTable) => {
    const expectedCountryOptions = dataTable.raw().slice(1).map((row) => row[0]).toString();

    //validate available countries in dropdown
    home.getCountryDropdownOptions().then((actualCountryOptions) => {
        expect(actualCountryOptions).to.eq(expectedCountryOptions);    
    });
});

When('user mouseover on the below mega menu', (dataTable) => {   
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const megaMenu = dataTable.rawTable[i][1].toString();
         
            if (testingCountry === country) {
                //mouseover on the mega menu
                header.mouseoverMegaMenu(megaMenu);
                break;  
            } else {
                cy.log(megaMenu + ' mega menu is not available for ' + testingCountry.toUpperCase() + ' country');
            }
        }
    });
});

Then('user should be able to see below available submenu options of {string} mega menu', (megaMenu, dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const submenu = dataTable.rawTable[i][1].toString().replace(/,/g, '').replace(/ /g, '');

            if (testingCountry === country) {              
                //validate the submenu options of given mega menu
                header.getSubmenuList(megaMenu).then((actualSubmenuList) => {
                    expect(actualSubmenuList.toLowerCase().replace(/\s+/g, '')).to.eq(submenu.toLowerCase());                     
                });
                break;
            } else {
                cy.log(megaMenu + ' mega menu is not available for ' + testingCountry.toUpperCase() + ' country');
            }
        }
    });
});

Then('user should be able to see below available submenu options of sale mega menu', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedSubmenuList = dataTable.rawTable[i][1].toString().replace(/,/g, '').replace(/ /g, '');
      
            if (testingCountry === country && testingCountry === 'us') {
                //get all level submenu options of sale mega menu
                header.getSubmenuHeader('Sale').then((actualSubmenuHeadersList) => {
                    header.getSubmenuList('Sale').then((actualSubmenusList) => {                   
                        const actualSubmenuList = actualSubmenuHeadersList.concat(actualSubmenusList);

                        //validate all level submenu options of sale mega menu
                        expect(actualSubmenuList.toLowerCase().replace(/\s+/g, '')).to.eq(expectedSubmenuList.toLowerCase());  
                    });
                });
                break;               
            } else if (testingCountry === country && ( testingCountry === 'eu' || testingCountry === 'uk')) {             
                //validate the submenu options of sale mega menu
                header.getSubmenuList('Sale').then((actualSubmenuList) => {
                    expect(actualSubmenuList.toLowerCase().replace(/ /g, '')).to.eq(expectedSubmenuList.toLowerCase());                     
                });          
            }
        }
    });   
});

Then('user should be able to see the search bar is visible', () => {
    //validate the search bar visible
    header.getSearchTextbox().should('be.visible');
});

Then('user should be able to see below available options of mega menu', (dataTable) => {   
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedMegaMenuList = dataTable.rawTable[i][1].toString().replace(/,/g, '').replace(/ /g, '');

            if (testingCountry === country) {              
                //validate the mega menu
                header.getMegaMenu().then((megaMenuList) => {
                    expect(megaMenuList.toLowerCase().replace(/,/g, '').replace(/ /g, '')).to.eq(expectedMegaMenuList.toLowerCase()); 
                });
                break; 
            } 
        }
    });
});

When('user click on the below option of country dropdown', (dataTable) => {   
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const dropdownOption = dataTable.rawTable[i][1];

            if (testingCountry === country) {     
                //click on the given country from the dropdown         
                home.clickCountryDropdownOption(dropdownOption);
                break;
            } 
        }
    });
});

Then('the website of respective country should be opened', (dataTable) => {   
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedCountryURL = dataTable.rawTable[i][1].toString();

            if (testingCountry === country) {              
                //validate the current page URL should be of the respective country
                home.getPageUrl().should('contain', expectedCountryURL);
                break;
            } 
        }
    });
});

When('user go back to the previous page', () => {   
    //go back to the previous page
    cy.go('back');
});