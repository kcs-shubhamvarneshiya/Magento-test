const { When, Then } = require ('@badeball/cypress-cucumber-preprocessor');

import { productDetails } from './productDescriptionPageSteps';
import ProjectsPage from '../pageObjects/projectsPage';
import HeaderPage from '../pageObjects/headerPage';

const projectsPage = new ProjectsPage();
const header = new HeaderPage();

When('user navigate to the {string} page', (expectedPageTitle) => {
    //validate the given page title
    projectsPage.getProjectsPageTitle().then((actualPageTitle) => {
        expect(actualPageTitle.toLowerCase()).to.be.equal(expectedPageTitle.toLowerCase());
    });
});

When('user add new {string} project', (projectName) => {
    //add new project 
    projectsPage.addNewProject(projectName);
});

When('user click on the {string} link of {string} project', (linkName, projectName) => {
    if (linkName.toLowerCase() === 'delete project') {
        //click on the delete project link of given project name
        projectsPage.clickDeleteProjectLink(projectName);
    } else if (linkName.toLowerCase() === 'view project') {
        //click on the view project link of given project name
        projectsPage.clickViewProjectLink(projectName);
    } else {
        throw new Error('The provided ' + linkName + ' link name is not valid. Valid link names are \"Delete Project\" or \"View Project\".');
    }
});

When('user click on the {string} button of delete project popup', (buttonName) => {
    if (buttonName.toLowerCase() === 'yes, delete project') {
        //click on the Yes, Delete Project button 
        projectsPage.clickYesDeleteProjectButton();
    } else {
        throw new Error('The provided ' + buttonName + ' button name is not valid. Valid button names are \"Yes, Delete Project\".');
    }
});

Then('the following success message should be displayed on the projects page', (dataTable) => {
    const successMessage = dataTable.rawTable[1].toString();

    //validate the success msg
    projectsPage.getSuccessMsg().should('contain.text', successMessage);
});

Then('the following project should not be displayed on the projects list', (dataTable) => {
    const deletedProjectName = dataTable.rawTable[1].toString();

    //validate the given project name should not exist on the projects list
    projectsPage.getProjectNameList().each(($displayedProjectName) => {
        cy.wrap($displayedProjectName).should('not.contain', deletedProjectName);
    });
}); 

When('user click on the checkbox of {string} project', (projectName) => {
    //click on the checkbox of given project name
    projectsPage.clickProjectCheckbox(projectName);
});

When('user click on the delete selected button', () => {
    //click on the delete selected button
    projectsPage.clickDeleteSelectedButton();
});

Then('details page of {string} project should get opened', (projectName) => {
    //validate whether the header of project details page contains the given name or not
    projectsPage.getProjectDetailsPageHeader().should('have.text', projectName);
});

When('user delete {string} project if present', (projectName) => {
    //delete the given project if exist
    projectsPage.deleteSpecificProject(projectName);
});

When('user click on the {string} project', (projectName) => {
    //click on the given project name
    projectsPage.clickProjectName(projectName);
});

When('user enter the {string} project name', (projectName) => {
    //enter the given project name
    projectsPage.enterProjectRename(projectName);
});

When('user click on the {string} button of rename project popup', (buttonName) => {
    if (buttonName.toLowerCase() === 'save') {
        //click on the save button 
        projectsPage.clickSaveProjectPopupButton();
    } else {
        throw new Error('The provided ' + buttonName + ' button name is not valid. Valid button names are \"Save\".');
    }
});

Then('user should be able to see below available headers on the projects page', (dataTable) => {
    cy.getCountry().then((testingCountry) => {
        for (let i = 1; i < dataTable.rawTable.length; i++) {
            const country = dataTable.rawTable[i][0].toLowerCase();
            const expectedHeaders = dataTable.rawTable[i][1].toString().replace(/ /g, '');

            if (testingCountry === country) {
                //validate the headers displayed on the page
                projectsPage.getTopHeaders().then((actualHeaders) => {
                    expect(actualHeaders.toLowerCase().replace(/\s+/g, '')).to.eq(expectedHeaders.toLowerCase());                     
                });
                break;  
            }
        }
    });
});

Then('user should be able to see {int} as the default pagination on the page', (expectedPaginationOption) => {
    //validate the default selected option of pagination
    projectsPage.getSelectedPaginationDropdownOption().then((actualSelectedPaginationOption) => {
        expect(actualSelectedPaginationOption).to.eq(expectedPaginationOption.toString());  
    });
});

Then('the item count should be the same as the added projects', () => {
    //load all projects
    projectsPage.selectPaginationDropdownOption("200");

    //validate the item count
    projectsPage.getItemCount().then((actualItemCount) => {
        projectsPage.getProjectNameList().then((projectList) => {
            const expectedItemCount = projectList.length;
            expect(Number(actualItemCount)).to.eq(Number(expectedItemCount));  
        });
    });
});

When('user click on the {string} link of product from the project page', (linkName) => {
    //click on the remove/edit ink
    if (linkName.toLowerCase() === 'remove') {
        projectsPage.clickRemoveLinkOfProduct();
    } else if (linkName.toLowerCase() === 'edit') {
        projectsPage.clickEditLinkOfProduct();
    } else {
        throw new Error(`The provided '${linkName}' link name is not valid. Valid name is Remove or Edit.`);
    }
});

Then('the product should not be displayed on the projects page', () => {
    //validate that the empty list message should be displayed
    projectsPage.getEmptyListMsg().should('be.visible');
});

When('user click on the add to cart button from the projects page', () => {
    //click on the add to cart
    projectsPage.clickAddToCart();
});

Then('the mini cart count should be increased by {int}', (expectedCount) => {
    //validate that the mini cart count should be increased
    header.getMiniCartCount().then((actualCount) => {
        expect(Number(actualCount)).to.be.equal(Number(expectedCount));
    });
});

Then('the following item details should be displayed on the projects page', (dataTable) => {
    const validLabel = ['Product Qty', 'Finish'];
   
    dataTable.hashes().forEach((row) => {
        const label = row.label.toLowerCase();

        //validate the product details displayed on the projects page
        switch (label) {
            case 'product qty':
                projectsPage.getProductQty().then((displayedProductQty) => {
                    expect(displayedProductQty).to.be.equal(productDetails.productQty);
                });
                break;
            case 'finish':
                projectsPage.getProductFinish().then((displayedProductFinish) => {
                    expect(displayedProductFinish).to.be.equal(productDetails.productFinish);
                });
                break;
            default:
                throw new Error(`The provided '${label}' label is not valid. Valid options are ${validLabel.join(', ')}.`);
        }
    });
});

When('user click on the create new project button from the projects page', () => {
    //click on the create new project
    projectsPage.clickCreateNewProjectButton();
});

When('user enter the {string} new project name', (projectName) => {
    //enter the given project name
    projectsPage.enterNewProjectName(projectName);
});

Then('the following message should be displayed on the project details page', (dataTable) => {
    const expectedMessage = dataTable.rawTable[1].toString();

    //validate the no items message should be displayed
    projectsPage.getNoItemsMsg().should('have.text', expectedMessage);
});

Then('the product ship by date of project page should be the same as displayed on the product description page', () => { 
    //validate that the displayed product ship by date should be the same as on the PDP
    projectsPage.getProductShipByDate().then((displayedShipByDate) => {
        expect(displayedShipByDate).to.equal(productDetails.shipByDate);
    });
});