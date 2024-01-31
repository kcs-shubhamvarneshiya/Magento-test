const { When, Then, Given } = require('@badeball/cypress-cucumber-preprocessor');

import FooterPage from '../pageObjects/footerPage';
const footer = new FooterPage();

Then('user should be able to see below details on the stay in touch section', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();
        const expectedValue = row.value;

        switch (label) {
            case 'title':
                //validate the Stay In Touch title text
                footer.getStayInTouchTitle().should('eql', expectedValue.toLowerCase());
                break;
            case 'text':
                //validate the Stay In Touch description text
                footer.getStayInTouchText().should('eql', expectedValue.toLowerCase());
                break;
            case 'textbox':
                //validate the email box is visible or not
                footer.getEmailBox().should('be.visible');
                break;
            case 'textbox placeholder':
                //validate the email box placeholder
                footer.getEmailBoxPlaceholder().should('eql', expectedValue.toLowerCase());
                break;
            case 'button':
                //validate the submit button is visible or not
                footer.getSubmitButton().should('be.visible');
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid.`);
        }
    });
});

When('user click on the country flag from the footer section', () => {
    //click on country flag
    footer.clickCountryFlag();
});

Then('user should be able to see below available countries in the footer section', (dataTable) => {
    const expectedCountryOptions = dataTable.raw().slice(1).map((row) => row[0]).toString();

    //validate available countries in dropdown
    footer.getCountryDropdownOptions().then((actualCountryOptions) => {
        expect(actualCountryOptions).to.eq(expectedCountryOptions);    
    });
});

Then('user should be able to see below link headers on the right side of the footer', (dataTable) => {
    const expectedRightSideLinkHeaders = dataTable.raw().slice(1).map((row) => row[0]).toString().replace(/\s+/g, '');

    //validate the link headers displayed on the right side
    footer.getRightSideLinkHeaders().then((displayedLinkHeaders) => {
        expect(displayedLinkHeaders.toLowerCase().replace(/\s+/g, '')).to.eq(expectedRightSideLinkHeaders.toLowerCase());
    });
});

Then('user should be able to see some links on the right side of the footer', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedRightSideLinks = dataTable.rawTable[i][1].toString().replace(/\s+/g, '');

            if (testingCountry === country) {
                //validate the links displayed on the right side
                footer.getRightSideLinks().then((displayedLinks) => {
                    expect(displayedLinks.toLowerCase().replace(/\s+/g, '')).to.eq(expectedRightSideLinks.toLowerCase());
                });
            }
        }
    });
});

Given('user change view port of browser to {int} and {int}', (width, height) => {
    //change the view point of browser
    footer.viewPoint(width, height);
});

Then('user should be able to see the experience visual comfort image', () => {
    //validate the experience visual comfort image should be displayed
    footer.getExperienceVisualComfortImage().should('be.visible');
});

Then('user should be able to see below copyright message', (dataTable) => {
    const expectedMessage = dataTable.raw().slice(1).map((row) => row[0]).toString().replace(/\s+/g, '');

    //validate the copyright message in footer
    footer.getCopyrightMessage().then((displayedMessage) => {
        expect(displayedMessage.toLowerCase()).to.eq(expectedMessage.toLowerCase());
    });
});

Then('user should be able to see country flag at the bottom of footer', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();

            if (testingCountry === country && testingCountry === 'us') {
                //validate the US flag should be displayed
                footer.getUSFlagIcon().should('be.visible');
            } else if (testingCountry === country && testingCountry === 'uk') {
                //validate the UK flag should be displayed
                footer.getUKFlagIcon().should('be.visible');
            } else if (testingCountry === country && testingCountry === 'eu') {
                //validate the EU flag should be displayed
                footer.getEUFlagIcon().should('be.visible');
            }
        }
    });
});

Then('the respective pages should open after clicking on all links of footer', () => {
    //get link data from the fixtures folder
    cy.fixture('footerLinks.json').then((data) => {
        cy.getCountry().then((testingCountry) => {

            //validate whether each footer link navigates to the respective page or not
            if (testingCountry === 'us') {
                data.USlinks.forEach(testLink => {
                    footer.validateLink(testLink);
                });
            } else if (testingCountry === 'uk') {
                data.UKlinks.forEach(testLink => {
                    footer.validateLink(testLink);
                });
            } else if (testingCountry === 'eu') {
                data.EUlinks.forEach(testLink => {
                    footer.validateLink(testLink);
                });
            }
        });
    });
});

When('user click on {string} link from footer', (linkText) => {
    //click on the given link
    footer.clickFooterLinkText(linkText);
});

Then('the page should open with the below page title', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const country = row.country.toLowerCase();
        const expectedPageTitle = row.title;

        //validate the page title
        cy.getCountry().then((testingCountry) => {
            if (testingCountry === country) {
                footer.getPageTitle().should('eql', expectedPageTitle);
            }
        });
    });
});

Then('the following social media icons should be displayed at the footer', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const icon = row.icons.toLowerCase();
        
        //validate the given icons should be displayed
        switch (icon) {
            case 'instagram':
                footer.getInstaIcon().should('be.visible');
                break;
            case 'pinterest':
                footer.getPinterestIcon().should('be.visible');
                break;
            default:
                throw new Error(`The provided '${icon}' icon is not valid.`);
        }
    });
});