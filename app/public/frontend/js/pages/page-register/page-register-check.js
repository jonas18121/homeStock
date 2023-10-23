import { FormCheckFunction } from '../../form/form-check-function';

const colorRed = '#dc3545';
const colorGreen = '#28a745';
const colorOrange = '#D07B21';

$(async function () {
    const formCheckFunction = new FormCheckFunction();

    // Last name
    checkLastName(formCheckFunction);

    // First name
    checkFirstName(formCheckFunction);

    // Email
    checkEmailWhileWriteIntoInput(formCheckFunction);
    await checkEmailAfterSubmit(formCheckFunction);

    // Password
    checkIfSamePasswordWhileWriteIntoInput(formCheckFunction);
    checkIfSameComfirmPasswordWhileWriteIntoInput(formCheckFunction);
});

/**
 * Vérifier Le prénom
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {void}
 */
function checkFirstName(formCheckFunction) {
    $(document).on('input change focusout', '#registration_firstName', function (event) {
        formCheckFunction.validInputLength(
            this,
            "#error_firstName",
            'Ce champ ne doit pas être vide',
            "Le prénom est trop court",
            "Trop de caractère",
            ""
        );
    });
}

/**
 * Vérifier Le prénom
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {void}
 */
function checkLastName(formCheckFunction) {
    $(document).on('input change focusout', '#registration_lastName', function (event) {
        formCheckFunction.validInputLength(
            this,
            "#error_lastName",
            'Ce champ ne doit pas être vide',
            "Le nom est trop court",
            "Trop de caractère",
            ""
        );
    });
}

/**
 * Vérifier si le mot de passe de comfirmation est égale au premier mot de passe 
 * pendant que l'user écrit dans l'input
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {voi}
 */
function checkIfSamePasswordWhileWriteIntoInput(formCheckFunction) {
    $(document).on('input change focusout', '#registration_password', function (event) {
        formCheckFunction.samePassword(
            this,
            "#registration_confirm_password",
            "#error_confirm_password",
            '',
            "Les deux passwords sont identique",
            "Les deux passwords ne sont pas identique",
            "Les deux passwords ne sont pas identique, il y a un caractère qui ne correspond pas ou il y a trop ou pas assé de caractères",
            colorGreen,
            colorGreen,
            colorRed,
            colorRed
        );
    });
}

/**
 * Vérifier si le mot de passe de comfirmation est égale au premier mot de passe 
 * pendant que l'user écrit dans l'input
 * 
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {void}
 */
function checkIfSameComfirmPasswordWhileWriteIntoInput(formCheckFunction) {
    $(document).on('input change focusout', '#registration_confirm_password', function (event) {
        formCheckFunction.sameComfirmPassword(
            this,
            "#registration_password",
            "#error_confirm_password",
            '',
            "Les deux passwords sont identique",
            "Les deux passwords ne sont pas identique",
            "Les deux passwords ne sont pas identique, il y a un caractère qui ne correspond pas",
            colorGreen,
            colorGreen,
            colorRed,
            colorRed
        );
    });
}


/**
 * Vérifier si l'email dans le champ est valide pendant que l'user écrit dans l'input
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {void}
 */
function checkEmailWhileWriteIntoInput(formCheckFunction) {
    $(document).on('input', async function (event) {
        formCheckFunction.isEmailExist(
            '#registration_email', 
            '/registration/email/', 
            '#error_email',
            'Cette adresse email est déjà utilisé.', 
            colorOrange, 
            colorOrange
        );
    });
}

/**
 * vérifier si l'email dans le champ est valide après le submit du formulaire
 * 
 * @param {FormCheckFunction} formCheckFunction 
 * 
 * @returns {void|boolean}
 */
function checkEmailAfterSubmit(formCheckFunction) {
    $(document).on('submit', '#user-registration-form', async function (event) {
        const formRegistration = $('#user-registration-form');
        
        formCheckFunction.stopEventAndBackToTop(event);

        // Supprimer le gestionnaire d'événement sumbit pour éviter une récursion infinie
        $(document).off('submit', '#user-registration-form');

		let data = [];
        await data.push(
            await formCheckFunction.isEmailExist(
                '#registration_email', 
                '/registration/email/', 
                '#error_email',
                'Cette email est déjà utilisé.', 
                colorRed, 
                colorOrange
            ) 
        );

        let isValid = formCheckFunction.isValidField(data);
        formCheckFunction.checkAfterSubmitAsync(isValid, event, formRegistration);
    });
}