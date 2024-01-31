const { When, Then } = require('@badeball/cypress-cucumber-preprocessor');

import { productDetails } from './productDescriptionPageSteps';
import QuotesPage from '../pageObjects/quotesPage';
import HeaderPage from '../pageObjects/headerPage';

const quotesPage = new QuotesPage();
const header = new HeaderPage();

let latestActivityDate;

Then('user should be able to see below available headers of quotes list', (dataTable) => {
    const expectedListHeaders = dataTable.raw().slice(1).map((row) => row[0]).toString();

    //validate available headers list
    quotesPage.getListHeaders().then((actualListHeaders) => {
        expect(actualListHeaders.toLowerCase()).to.eq(expectedListHeaders.toLowerCase());
    });
});

When('user enter {string} project name in create quote', (projectName) => {
    //enter project name
    quotesPage.enterProjectName(projectName);
});

When('user click on save quote button', () => {
    //click save quote button
    quotesPage.clickOnSaveButton();
});

When('user delete {string} project from {string} page', (projectName, page) => {
    //go to the projects page
    header.gotoProfileOptionPage(page);

    //delete a single project
    quotesPage.deleteSpecificQuote(projectName);
});

Then('the item count should be the same as the added quotes', () => {
    //load all quotes
    quotesPage.selectPaginationDropdownOption("20");

    //validate the item count with added quotes
    quotesPage.getItemCount().then((actualItemCount) => {
        quotesPage.getProjectNameList().then((projectList) => {
            const expectedItemCount = projectList.length;
            expect(Number(actualItemCount)).to.eq(Number(expectedItemCount));
        });
    });
});

When('user collect the following detail of {string} project', (projectName) => {
    latestActivityDate = '';

    //collect the latest activity date 
    quotesPage.getLatestActivityDate(projectName).then((displayedLatestActivity) => {
        latestActivityDate = displayedLatestActivity.replace('0', '');
    });
});

When('user click on view details of {string} project', (projectName) => {
    //click on the view link of given project
    quotesPage.clickViewLink(projectName);
});

Then('user should be able to see below detail on the quote details page', (dataTable) => {
    dataTable.hashes().forEach((row) => {
        const expectedProjectName = row.projectName.toLowerCase();
        const expectedDate = latestActivityDate;

        //validate the displayed project name 
        quotesPage.getProjectNameDetailPage().then((displayedProjectName) => {
            expect(displayedProjectName).to.eq(expectedProjectName);
        });

        //validate the displayed date 
        quotesPage.getLatestActivityDetailPage().then((displayedDate) => {
            expect(displayedDate).to.eq(expectedDate);
        });
    });
});

When('user click on the edit link of product from the quote details page', () => {
    //click on the edit link of product
    quotesPage.clickProductEditLink(productDetails.productName);
});


Then('the following item details should be displayed on the quote details page', (dataTable) => {
    const validLabel = ['Product Qty'];

    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the quote details page
        switch (label) {
            case 'product qty':
                quotesPage.getProductQty(productDetails.productName).then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

When('user click on the remove link of product from the quote details page', () => {
    //click on the remove link of product
    quotesPage.clickProductRemoveLink(productDetails.productName);
});

When('user click on the delete button of remove from quote popup', () => {
    //click on the delete button of remove from quote popup
    quotesPage.clickDeleteButtonOfRemoveFromQuotePopup();
});

Then('the removed product should not be displayed on the quote details page', () => {
    //validate the removed product name should not be displayed 
    quotesPage.getRemovedProductName(productDetails.productName).should('not.exist');
});

Then('user should be able to see below details of {string} project', (projectName, dataTable) => {
    let currentDate = new Date();
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    let formattedDate = `${monthNames[currentDate.getMonth()]} ${currentDate.getDate()}, ${currentDate.getFullYear()}`;

    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const label = dataTable.rawTable[i][1].toLowerCase();
            const expectedValue = dataTable.rawTable[i][2];

            //validate project details displayed on the quotes list page
            if (testingCountry === country) {
                switch (label) {
                    case 'project name':
                        cy.validateDisplayedText(quotesPage.getProject(projectName), expectedValue);
                        break;
                    case 'created by':
                        cy.validateDisplayedText(quotesPage.getCreatedBy(projectName), expectedValue);
                        break;
                    case 'quote total':
                        quotesPage.getQuoteTotal(projectName).should('be.visible');
                        break;
                    case 'view quote details':
                        quotesPage.getViewLink(projectName).should('be.visible');
                        break;
                    case 'latest activity':
                        quotesPage.getLatestActivityDate(projectName).then((actualDetails) => {
                            expect(actualDetails).to.eq(formattedDate);
                        });
                        break;
                    default:
                        throw new Error(`The provided '${label}' label is not valid.`);
                }
            }
        }
    });
});

When('user click on create new quote button', () => {
    //click on create new quote button from quotes page
    quotesPage.clickCreateNewQuoteButton();
});