const stayInTouchTitle = 'div.block.newsletter > div[class="title"]';
const stayInTouchText = 'div.block.newsletter div[class="copy"]';
const emailBox = 'div.block.newsletter input#newsletter';
const submitButton = 'div.block.newsletter button[title="Sign Up"]';
const countryFlag = 'div[class="footer-inner"] > div.locations-dropdown > span[data-toggle="dropdown"]';
const countryDropdownOptions = 'div[class="footer-inner"] > div.locations-dropdown.footer.active > ul.dropdown > li';
const rightSideLinkHeaders = 'div#vc-footer-links p[class="footer-header"]';
const rightSideLinks = 'div#vc-footer-links a[class="footer-link"]';
const experienceVisualComfortImage = 'div[class="vc-experience"] > img';
const copyRightMessage = 'div.copy-right span[class="copy"]';
const usFlagIcon = 'div[class="footer-inner"] > div.locations-dropdown > span.action.toggle.flag.flagUS';
const ukFlagIcon = 'div[class="footer-inner"] > div.locations-dropdown > span.action.toggle.flag.flagUK';
const euFlagIcon = 'div[class="footer-inner"] > div.locations-dropdown > span.action.toggle.flag.flagEU';
const footerLinks = 'div#vc-footer-links div[data-role="content"] > a[href*="';
const footerLinkText = 'div#vc-footer-links div[data-role="content"] > a';
const instaIcon = 'footer.page-footer div.social-icons > a.footer-insta > img';
const pinterestIcon = 'footer.page-footer div.social-icons > a.footer-pint > img';

class FooterPage {

    getStayInTouchTitle () {
        cy.scrollTo('bottom');
        return cy.get(stayInTouchTitle).then((displayedTitle) => {
            return displayedTitle.text().trim().toLowerCase();
        });
    }

    getStayInTouchText () {
        return cy.get(stayInTouchText).then((displayedText) => {
            return displayedText.text().trim().toLowerCase();
        });
    }

    getEmailBox () {
        return cy.get(emailBox);
    }

    getEmailBoxPlaceholder () {
        return cy.get(emailBox).invoke('attr', 'placeholder').then((placeholder) => {
            return placeholder.trim().toLowerCase();
        });
    }

    getSubmitButton () {
        return cy.get(submitButton);
    }

    clickCountryFlag () {
        cy.waitForSometime();
        cy.get(countryFlag).click();
    }

    getCountryDropdownOptions () {
        return cy.getListElement(countryDropdownOptions, ",");    
    }

    getRightSideLinkHeaders () {
        cy.scrollTo('bottom');
        cy.waitForSometime();
        return cy.getListElement(rightSideLinkHeaders, ",");
    }

    getRightSideLinks () {
        cy.scrollTo('bottom');
        return cy.getListElement(rightSideLinks, ",");
    }

    viewPoint (width, height) {
        cy.viewport(width, height);
    }

    getExperienceVisualComfortImage () {
        return cy.get(experienceVisualComfortImage);
    }

    getCopyrightMessage () {
        return cy.get(copyRightMessage).then((displayedText) => {
            return displayedText.text().replace(/\s+/g, '').trim();
        });
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

    clickFooterLinks (testLink) {
        cy.get(footerLinks + testLink + '"]').click();
    }

    getPageTitle () {
        return cy.title();
    }

    validateLink (data) {
        this.clickFooterLinks(data.link);
        this.getPageTitle().should('eql', data.title);
        cy.go('back');
    }

    clickFooterLinkText (value) {
        cy.contains(footerLinkText, value, { matchCase: false }).click();
    }

    getInstaIcon () {
        return cy.get(instaIcon);
    }

    getPinterestIcon () {
        return cy.get(pinterestIcon);
    }

} export default FooterPage