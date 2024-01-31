const homePageTitleUS = 'Signature Designer Light Fixtures | Experience Visual Comfort & Co.';
const homePageTitleUK = 'Signature Designer Lighting | Experience Visual Comfort & Co.';
const homePageTitleEU = 'Signature Designer Lighting | Experience Visual Comfort & Co.';
const countryFlag = 'div.locations-dropdown > span.action.toggle > div';
const usFlagIcon = 'header.page-header div.locations-dropdown > span[data-toggle="dropdown"] > div.flag.flagUS';
const ukFlagIcon = 'header.page-header div.locations-dropdown > span[data-toggle="dropdown"] > div.flag.flagUK';
const euFlagIcon = 'header.page-header div.locations-dropdown > span[data-toggle="dropdown"] > div.flag.flagEU';
const countryDropdownOptions = '.locations-dropdown.active > ul[data-target="dropdown"] span';

class HomePage {

    getHomePageTitle () {
        return cy.title();
    }

    openHomePageUrl () {
        cy.getBaseUrl().then((baseUrl) => {
            cy.visit(baseUrl);
            cy.waitForSometime();
        });
    }

    validateHomePageTitle () {
        cy.getCountry().then((country) => {
            if (country === 'us') {
                this.getHomePageTitle().should('contain', homePageTitleUS);
            } else if (country === 'uk') {
                this.getHomePageTitle().should('contain', homePageTitleUK);
            } else if (country === 'eu') {
                this.getHomePageTitle().should('contain', homePageTitleEU);
            }
        });
    }

    clickCountryFlag () {
        cy.waitForSometime();
        cy.get(countryFlag).click();
    }

    getCountryDropdownOptions () {
        return cy.getListElement(countryDropdownOptions, ",");    
    }

    getUSFlagIcon () {
        return cy.get(usFlagIcon);
    }

    getUKFlagIcon () {
        return cy.get(ukFlagIcon);
    }

    getEUFlagIcon () {
        return cy.get(euFlagIcon);
    }

    clickCountryDropdownOption (option) {
        cy.contains(countryDropdownOptions, option, { matchCase: false }).siblings('div.flag').should('be.visible');
        cy.contains(countryDropdownOptions, option, { matchCase: false }).should('be.visible').click();    
    }

    getPageUrl () {
        return cy.url();
    }

} export default HomePage