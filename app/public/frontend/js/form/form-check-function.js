////// Generic Function ////// 

export class FormCheckFunction {

    constructor() {
        
    }

    /**
     * Ecrire du texte avec une couleur temporaire
     * 
     * Peut être buger en async
     * 
     * @param {Object} textElementDom 
     * @param {String} text 
     * @param {String} temporaryColor 
     * @param {String} permanentColor 
     * @param {int} time 
     */
    writeTextWithTemporaryColor(textElementDom, text, temporaryColor, permanentColor, time)
    {
        textElementDom.text(text)
        .css('color', temporaryColor)
        .delay(time)
        .queue(function (next) {
            $(this).css('color', permanentColor);
            next();
        });
    }

    /**
     * Ecrire du texte avec une couleur
     * 
     * @param {Object} textElementDom 
     * @param {String} text 
     * @param {String} permanentColor 
     * @param {int} time 
     */
    writeTextWithColor(textElementDom, text, temporaryColor)
    {
        textElementDom.text(text).css('color', temporaryColor);
    }

    /**
     * Vérifier si les caractères du password sont pareil que ceux de comfirm_password
     * 
     * @param {string} passwordId : Password Id
     * @param {string} comparePasswordId : compare confirm Password Id
     * @param {string} tagError : tag error
     * @param {string} messageSuccessGeneral : success message general, the field is empty
     * @param {string} messageSuccessEqual : success message Equal, the field is empty
     * @param {string} messageErrorGeneral : error message general, the field is empty
     * @param {string} messageErrorSmall : error message Small, the field is empty
     * @param {string} permanentColorSuccessGeneral : permanent color Success General
     * @param {string} permanentColorSuccessEqual : permanent color Success Equal
     * @param {string} permanentColorErrorGeneral : permanent color Error General
     * @param {string} permanentColorErrorSmall : permanent color Error Small
     * 
     * @returns {boolean}
     */
    samePassword(
        passwordId, 
        compareComfirmPasswordId, 
        tagError, 
        messageSuccessGeneral,
        messageSuccessEqual,
        messageErrorGeneral,
        messageErrorSmall,
        permanentColorSuccessGeneral,
        permanentColorSuccessEqual,
        permanentColorErrorGeneral,
        permanentColorErrorSmall
    ) {
        const compare = $(compareComfirmPasswordId).val(); // La chaîne de comparaison

        const inputText = $(passwordId).val(); // Récupérer la valeur du champ de formulaire
        const textError = $(tagError);

        let isMatch = true;
    
        if (compare !== null && compare !== undefined && compare !== '') {
            for (let i = 0; i < inputText.length && i < compare.length; i++) {
                if (inputText[i] !== compare[i]) {
                    isMatch = false;
                    break; // Sortir de la boucle dès qu'une différence est trouvée
                }
            }
    
            if (inputText.length > compare.length || inputText.length < compare.length) {
                isMatch = false;
            }
        
            if (isMatch) {
                this.writeTextWithColor(
                    textError, 
                    messageSuccessGeneral,
                    permanentColorSuccessGeneral
                );
    
                if (inputText.length === compare.length) {
                    this.writeTextWithColor(
                        textError, 
                        messageSuccessEqual, 
                        permanentColorSuccessEqual
                    );
                }
    
                return true;
            } else {
                this.writeTextWithColor(
                    textError, 
                    messageErrorGeneral, 
                    permanentColorErrorGeneral
                );
    
                if (inputText.length < compare.length) {
                    this.writeTextWithColor(
                        textError, 
                        messageErrorSmall, 
                        permanentColorErrorSmall
                    );
                }
    
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifier si les caractères du comfirm_password sont pareil que ceux de password
     * 
     * @param {string} comfirmPasswordId : comfirm Password Id
     * @param {string} comparePasswordId : compare Password Id
     * @param {string} tagError : tag error
     * @param {string} messageSuccessGeneral : success message general, the field is empty
     * @param {string} messageSuccessEqual : success message Equal, the field is empty
     * @param {string} messageErrorGeneral : error message general, the field is empty
     * @param {string} messageErrorSmall : error message Small, the field is empty
     * @param {string} permanentColorSuccessGeneral : permanent color Success General
     * @param {string} permanentColorSuccessEqual : permanent color Success Equal
     * @param {string} permanentColorErrorGeneral : permanent color Error General
     * @param {string} permanentColorErrorSmall : permanent color Error Small
     * 
     * @returns {boolean}
     */
    sameComfirmPassword(
        comfirmPasswordId, 
        passwordId, 
        tagError, 
        messageSuccessGeneral,
        messageSuccessEqual,
        messageErrorGeneral,
        messageErrorSmall,
        permanentColorSuccessGeneral,
        permanentColorSuccessEqual,
        permanentColorErrorGeneral,
        permanentColorErrorSmall
    ) {
        const compare = $(passwordId).val(); // La chaîne de comparaison

        const inputText = $(comfirmPasswordId).val(); // Récupérer la valeur du champ de formulaire
        const textError = $(tagError);

        let isMatch = true;
    
        if (inputText !== null && inputText !== undefined && inputText !== '') {
            for (let i = 0; i < inputText.length && i < compare.length; i++) {
                if (inputText[i] !== compare[i]) {
                    isMatch = false;
                    break; // Sortir de la boucle dès qu'une différence est trouvée
                }
            }
    
            // if (inputText.length > compare.length) {
            //     isMatch = false;
            // }

            if (inputText.length !== compare.length) {
                isMatch = false;
            }
        
            if (isMatch) {
                this.writeTextWithColor(
                    textError, 
                    messageSuccessGeneral, 
                    permanentColorSuccessGeneral
                );
    
                if (inputText.length === compare.length) {
                    this.writeTextWithColor(
                        textError, 
                        messageSuccessEqual,  
                        permanentColorSuccessEqual
                    );
                }
    
                return true;
            } else {
                this.writeTextWithColor(
                    textError, 
                    messageErrorGeneral, 
                    permanentColorErrorGeneral
                );
    
                if (inputText.length < compare.length) {
                    this.writeTextWithColor(
                        textError, 
                        messageErrorSmall,  
                        permanentColorErrorSmall
                    );
                }
    
                return false;
            }
        }

        return true;
    }

    /**
     * @param {string} input : input id
     * @param {string} partUrl : part of the url
     * @param {string} messageError : error message, the field is empty
     * @param {string} temporaryColor : temporary color
     * @param {string} permanentColor : permanent color
     * 
     * @returns {string}
     */
    async isEmailExist(input, partUrl, messageError, temporaryColor, permanentColor) {
        try {
            const isValid = await this.findEmailExist(input, partUrl, messageError, temporaryColor, permanentColor);
            console.log(isValid);
            return isValid;
        } catch (error) {
            alert('Une erreur s\'est produite : ' + error);
            throw error;
        }
    }

    /**
     * @param {string} input : input id
     * @param {string} partUrl : part of the url
     * @param {string} tagError : tag error
     * @param {string} messageError : error message, the field is empty
     * @param {string} temporaryColor : temporary color
     * @param {string} permanentColor : permanent color
     * 
     * @returns {Promise}
     */
    async findEmailExist(input, partUrl, tagError, messageError, temporaryColor, permanentColor) 
    {
        return new Promise(function(resolve, reject) {
            let email = $(input).val();
            let isValid = false;
            
            if (email !== 'undefined' && email !== undefined && email !== 0 && email !== '') {

                let baseUrl = window.location.origin;
                $.ajax({
                    type: 'GET',
                    url: baseUrl + partUrl + email,
                    success: function (response) {

                        if (response.result == 'success') {
                            $(tagError).text('');

                            isValid = true;
                        } 
                        else {
                            $(tagError).text(messageError)
                            .css('color', temporaryColor)
                            .delay(50000)
                            .queue(function (next) {
                                $(this).css('color', permanentColor);
                                next();
                            });

                            isValid = false;
                        }

                        resolve(isValid);
                    },
                    error: function (xhr, textStatus, error) {
                        alert('Error : ' + xhr.statusText + '.');
                        reject(error);
                        // Use toastr
                        // toastr.error(
                        // 	Translator.trans('js.error_occured', {}, 'javascript') + ' : ' + xhr.statusText + '.',
                        // );
                    },
                    complete: function (data) {
                        // Nothing
                    },
                });
            } 
            else {
                resolve(isValid); // Si l'email est vide, résolvez la promesse avec la valeur actuelle de isValid (false).
            } 
        });       
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
        // console.log(data);
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
    checkAfterSubmit(isValid, event){
        if (isValid === false) {
            this.stopEventAndBackToTop(event);
        }
    }

    /**
     * check if isValid is correct on submit on async whit submit
     * 
     * @param {boolean} isValid
     * @param {event} event
     * @param {Object} form
     * 
     * @returns {void}
     */
    async checkAfterSubmitAsync(isValid, event, form){
        if (isValid === false) {
            this.stopEventAndBackToTop(event);
        } else {
            form.submit();
        }
    }

       /**
     * check if isValid is correct on submit on async  whitout submit
     * 
     * @param {boolean} isValid
     * @param {event} event
     * @param {Object} form
     * 
     * @returns {void}
     */
       async checkAfterSubmitAsyncWhitoutSubmit(isValid, event){
        if (isValid === false) {
            this.stopEventAndBackToTop(event);
        }
    }

    /**
     * check if isValid is correct before submit
     * 
     * @param {boolean} isValid
     * @param {event} event
     * @param {string} buttonId
     * @param {boolean} isDisabled
     * 
     * @returns {boolean}
     */
    checkBeforeSubmit(isValid, event, buttonId, isDisabled = false){
        if (isValid === false) {
            if (isDisabled === true) {
                event.preventDefault(); // stop submit
                $(buttonId).prop('disabled', true);
                // $('html,body').animate({scrollTop: 0}, 'slow'); // back to top
            }

            console.log('return false');
            return false;
        }
        else {
            if (isDisabled === true) {
                $(buttonId).prop('disabled', false);
            }

            console.log('return true');
            return true;
        }
    }

    /**
     * check if isValid is correct before submit
     * 
     * @param {boolean} isValid
     * @param {event} event
     * @param {string} buttonSubmitId
     * @param {boolean} isDisabled
     * 
     * @returns {boolean}
     */
    async checkBeforeSubmitAsync(isValid, event, buttonSubmitId, isDisabled = false){
        if (isValid === false) {
            if (isDisabled === true) {
                event.preventDefault(); // stop submit
                $(buttonSubmitId).prop('disabled', true);
                // $('html,body').animate({scrollTop: 0}, 'slow'); // back to top
            }

            return false;
        }
        else {
            if (isDisabled === true) {
                $(buttonSubmitId).prop('disabled', false);
            }

            return true;
        }
    }

    /**
     * 
     * @param {Event} event 
     * 
     * @returns {void}
     */
    stopEventAndBackToTop (event)
    {
        event.preventDefault(); // stop submit
        $('html,body').animate({scrollTop: 0}, 'slow'); // back to top
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