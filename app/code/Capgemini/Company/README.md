CONCERNING CREATING COMPANY ADMIN DEFAULT BILLING AND SHIPPING ADDRESS BASED ON COMPANY LEGAL ADDRESS.

Earlier when a company was created from the frontend (Trade Account Request) not only the company's admin was created together with the company but also an address for that admin was created based on the company's legal address.  
However things looked different when a company was created from the admin panel - only the company's admin was created and no address with that.
So the functionality that created a customer address for a company admin was removed from the frontend controller responsible for creating a company and put in a separate class of \Capgemini\Company\Helper\CustomerAddress.
That functionality now is being referred and utilized by both frontend and adminhtml controllers used in a company creation.
Thus, a customer address based on the company's Legal Address is now created for a company admin when the company is being created not only on the storefront, but also in the admin panel. 
