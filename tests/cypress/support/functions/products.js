import ProductListPage from '../../e2e/pageObjects/productListPage';
const listPage = new ProductListPage();

//check whether the product is present or not on the list
Cypress.Commands.add('findProductCode', (productCode) => {
    let isFound = 'no', pageNumber = 1;

    function findProduct (productCode) {
        listPage.getProductCodeList().then(($list) => {
            const productList = Cypress.$($list).text().trim().toLowerCase();
    
            if (productList.includes(productCode.toLowerCase())) {
                isFound = 'yes';
                cy.log('The product is found on the '+ pageNumber +' page.');
            } else {
                pageNumber++;
                listPage.getNextPage().click().then(() => {
                    if (isFound === 'no') {
                        findProduct(productCode); 
                    }
                });
            }
        });
    }
    
    findProduct(productCode); 
});