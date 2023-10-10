////// Generic Function ////// 

export class FormCheckFunction {

    constructor() {
        
    }

    /**
     * Checking the length of an input field's value
     * 
     * @param {string} input : input id
     * @param {string} tagError : id of the div to display the error message
     * @param {int} limit : number of characters not to be exceeded
     * @param {string} messageErrorEmpty : error message, the field is empty
     * @param {string} messageSuccess : success message
     * 
     * @returns {boolean}
     */
    validInputZipCode(input, tagError, limit, messageErrorEmpty, messageErrorFormat, messageSuccess = null){
        let color, message = null;
        let validInputError = $(tagError);
        let validInput = $(input);
        let regex = new RegExp("^[0-9]{" + limit + "}$");

        let isValid = false;

        if(validInput.val() == ""){
            message = messageErrorEmpty;
            color = "red";
        }
        else if(!regex.test(validInput.val())){
            message = messageErrorFormat;
            color = "red";
        }
        else {
            message = messageSuccess;
            color = "green";
            isValid = true;
        }

        validInputError.html(message).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * Checks if the select field is disabled
     * 
     * @param {string} input : input id
     * @param {string} tagError : id of the div to display the error message
     * @param {string} messageErrorEmpty : error message, the field is empty
     * @param {string} messageSuccess : success message
     * 
     * @returns {boolean}
     */
    validInputSelect(input, tagError, messageErrorEmpty, messageSuccess = null){
        let color, message = null;
        let validInputError = $(tagError);
        let validInput = $(input);

        let isValid = false;

        if(validInput.val() != "" && validInput.val() != undefined){
            message = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(validInput.val() == "" || validInput.val() == undefined){
            message = messageErrorEmpty;
            color = "red";
        }

        validInputError.html(message).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * checks the length of an input
     * 
     * @param {string} input : input id
     * @param {string} tagError : id of the div to display the error message
     * @param {string} messageErrorEmpty : error message, the field is empty
     * @param {string} messageErrorSmall : error message, the number of characters is too small
     * @param {string} messageErrorTall : error message, the number of characters is too large
     * @param {string} messageSuccess : success message
     * 
     * @returns {boolean}
     */
    validInputLength(input, tagError, messageErrorEmpty, messageErrorSmall = null, messageErrorTall = null, messageSuccess = null){
        let color, message = null;
        let validInputError = $(tagError);
        let validInput = $(input);

        let isValid = false;

        if(validInput.val().length >= 2 && validInput.val().length <= 255){
            message = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(validInput.val() == ""){
            message = messageErrorEmpty;
            color = "red";
        }
        else if(validInput.val().length < 2){
            message = messageErrorSmall;
            color = "red";
        }
        else if(validInput.val().length > 255){
            message = messageErrorTall;
            color = "red";
        }
        validInputError.html(message).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * checks if the boolean false exist
     * 
     * @param {boolean[]} data : array of boolean
     * 
     * @returns {boolean}
     */
    isValidField(data) {
        for (let index = 0; index < data.length; index++) {
            if(false == data[index]){
                return false;
            }
        }

        return true;
    }

    /**
     * check if the email is correct
     * 
     * @param {string} input : input id
     * @param {string} tagError : id of the div to display the error message
     * @param {string} messageErrorEmpty : error message, the field is empty
     * @param {string} messageErrorFormat : error message, the format is not correct
     * @param {string} messageSuccess : success message
     * 
     * @returns {boolean}
     */
    validEmail(input, tagError, messageErrorEmpty, messageErrorFormat, messageSuccess = null){
        let color, message = null;
        let emailError = $(tagError);
        let email = $(input);
        let regex = /^[a-zA-Z][a-zA-Z0-9._/-]{1,255}@[a-z]{3,150}\.[a-z]{2,3}$/;

        let isValid = false;

        if(email.val() == ""){
            message = messageErrorEmpty;
            color = "red";
        }
        else if(regex.test(email.val())){
            message = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(!regex.test(email.val())){
            message = messageErrorFormat;
            color = "red";
        }
        
        emailError.html(message).text();
        emailError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * check if the phone number is correct
     * 
     * @param {string} input : input id
     * @param {string} tagError : id of the div to display the error message
     * @param {string} messageErrorFormat : error message, the format is not correct
     * @param {string} messageErrorEmpty : error message, the field is empty
     * @param {string} messageSuccess : success message
     * 
     * @returns {boolean}
     */
    validInputPhonenumber(input, tagError, messageErrorFormat, messageErrorEmpty, messageSuccess = null){
        let color, message = null;
        let validInputError = $(tagError);
        let validInput = $(input);
        let regex = /^(?:(?:\+|00)33|0)\s*[12345679](?:[\s.-]*\d{2}){4}$/

        let isValid = false;

        if(validInput.val() == ""){
            message = messageErrorEmpty;
            color = "red";
        }
        else if(regex.test(validInput.val())){
            message = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(!regex.test(validInput.val())){
            message = messageErrorFormat;
            color = "red";
        }
        
        validInputError.html(message).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * check if isValid is correct on submit
     * 
     * @param {boolean} isValid
     * @param {event} event
     * 
     * @returns {void}
     */
    checkOnSubmit(isValid, event){
        if (isValid == false) {
            event.preventDefault(); // stop submit
            $('html,body').animate({scrollTop: 0}, 'slow'); // back to top
        }
    }

    /**
    * Pour les input de type date
    * input : id de l'input
    * inputError : id de la div pour affiché les message d'erreurs
    * messageError : message d'erreur
    * messageSuccess : message de success
    * messageErrorDateSmall : message d'erreur si la date saisie dans l'input est plus petit que la date du jour 
    */
        validInputDateBirthdate(input, inputError, messageError, messageErrorEmpty, messageSuccess = null, messageErrorDateSmall = null){
        let validInputError = $(`#${inputError}`);
        let validInput = $(`#${input}`);
        let regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;

        let isValid = false;

        let dateCurrent = new Date();
        let dateInput = new Date(validInput.val());

        if (dateCurrent.getFullYear()-100 > dateInput.getFullYear()){
            length = messageErrorDateSmall;
            color = "red";
        }
        else if(validInput.val() == ""){
            length = messageErrorEmpty;
            color = "red";
        }
        else if(regex.test(validInput.val())){
            length = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(!regex.test(validInput.val())){
            length = messageError;
            color = "red";
        }
                  
        validInputError.html(length).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
    * Pour les input de type date
    * input : id de l'input
    * inputError : id de la div pour affiché les message d'erreurs
    * messageError : message d'erreur
    * messageSuccess : message de success
    * messageErrorDateSmall : message d'erreur si la date saisie dans l'input est plus petit que la date du jour 
    */
    validInputDateIdentity(input, inputError, messageError, messageErrorEmpty, messageSuccess = null, messageErrorDateSmall = null){
        let validInputError = $(`#${inputError}`);
        let validInput = $(`#${input}`);
        let regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;

        let isValid = false;

        // Date current
        let dateCurrent = new Date();
        let dateCurrentDate = dateCurrent.getDate();
        dateCurrentDate = dateCurrentDate.toString();
        if(dateCurrentDate.length < 2){
            dateCurrentDate = 0 + '' + dateCurrentDate;
        }

        let dateCurrentMonth = dateCurrent.getMonth()+1;
        dateCurrentMonth = dateCurrentMonth.toString();
        if(dateCurrentMonth.length < 2){
            dateCurrentMonth = 0 + '' + dateCurrentMonth;
        }
        dateCurrent = parseInt(dateCurrent.getFullYear() + '' + dateCurrentMonth + '' + dateCurrentDate);
        //////////////////////////////////////// End date current //////////////////////////////////////////////////////

        // Date of input
        validInputArray = validInput.val().split('/');
        
        //                  param #1 = month,              param #2 = day,              param #3 = year,
        let retrievedDate = validInputArray['1'] + '/' + validInputArray['0']  + '/' + validInputArray['2']

        let dateInput = new Date(retrievedDate);

        let dateInputDate = dateInput.getDate();
        dateInputDate = dateInputDate.toString();
        if(dateInputDate.length < 2){
            dateInputDate = 0 + '' + dateInputDate;
        }

        let dateInputMonth = dateInput.getMonth()+1;
        dateInputMonth = dateInputMonth.toString();
        if(dateInputMonth.length < 2){
            dateInputMonth = 0 + '' + dateInputMonth;
        }
        dateInput = parseInt(dateInput.getFullYear() + '' + dateInputMonth + '' + dateInputDate);
        ////////////////////////////////////// End date of input ////////////////////////////////////////////////////////

        // Compare date
        if(dateCurrent >= dateInput){
            length = messageErrorDateSmall;
            color = "red";
        }
        else if(validInput == "" || isNaN(dateInput)){
            length = messageErrorEmpty;
            color = "red";
        }
        else if(regex.test(validInput.val())){
            length = messageSuccess;
            color = "green";
            isValid = true;
        }
        else if(!regex.test(validInput.val())){
            length = messageError;
            color = "red";
        }    

        validInputError.html(length).text();
        validInputError.css({ "color": `${color}`});

        return isValid;
    }

    /**
     * Empty value of Input
     * 
     * @param {string[]} elementsInput 
     * 
     * @returns {void} 
     */
    emptyInput(elementsInput) {
        for (let index = 0; index < elementsInput.length; index++) {
            $(elementsInput[index]).val('');
        }
    }

    /**
     * Reset value of select to first option
     * 
     * @param {string[]} elementsSelect 
     * 
     * @returns {void} 
     */
    resetSelect(elementsSelect) {
        for (let index = 0; index < elementsSelect.length; index++) {
            $(elementsSelect[index]).val($(elementsSelect[index] + ' option:first').val());
        }
    }
}