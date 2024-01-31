//return a unique email after appending a random number with current date
Cypress.Commands.add('createUniqueEmail', (email) => {
    //extract the year, month, and day components of the current date
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');

    //generate a random number
    const randomNumber = Math.floor(Math.random() * 1000);

    //split the email address into local part and domain part
    const parts = email.split("@");
    const localPart = parts[0];
    const domainPart = parts[1];

    //append the random number with current date after the local part with the '+' sign
    const newLocalPart = localPart + '+' + `${day}${month}${year}${randomNumber}`;

    //return the new email address
    return newLocalPart + "@" + domainPart;
});

//return the price as a number after removing extra characters
Cypress.Commands.add('convertPriceToNumber', (price, currencySymbol) => {
    let num = price.split(currencySymbol);
    num = num[1].trim();
    num = num.replace(',','');
    num = Number(num);
    return num;   
});

//return the string as a number
Cypress.Commands.add('convertStringToNumber', (string) => {
    let num = string.trim();
    num = Number(num);
    return num;   
});

//return the formatted MM DD YYYY date after adding the given days to the current date
Cypress.Commands.add('addDaysToCurrentDate', (days, symbol) => {
    //get the current date
    const currentDate = new Date();

    //add days to the current date
    currentDate.setDate(currentDate.getDate() + days);

    //extract the year, month, and day components of the date
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');

    //return the formatted MM DD YYYY date 
    return `${month}${symbol}${day}${symbol}${year}`;
});

//return a unique name after appending a random number with current date
Cypress.Commands.add('createUniqueName', (name) => {
    //extract the year, month, and day components of the current date
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');

    //generate a random number
    const randomNumber = Math.floor(Math.random() * 1000);

    //append the random number with current date after the name with the '+' sign
    const newName = name + '+' + `${day}${month}${year}${randomNumber}`;

    //return the new name
    return newName;
});