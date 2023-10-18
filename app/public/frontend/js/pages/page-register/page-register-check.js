import { FormCheckFunction } from '../../form/form-check-function';

const colorRed = '#dc3545';
const colorGreen = '#28a745';
const colorOrange = '#D07B21';

$(function () {
    const formCheckFunction = new FormCheckFunction();
    checkEmailWhileWriteIntoInput(formCheckFunction);
    checkEmailAfterSubmit(formCheckFunction);
});

/**
 *  vérifier si l'email dans le champ est valide pendant que l'user écrit dans l'input
 * 
 * @param {FormCheckFunction} formCheckFunction 
 */
function checkEmailWhileWriteIntoInput(formCheckFunction) {
    $(document).on('input', async function (event) {
        await formCheckFunction.isEmailExist(
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
 */
function checkEmailAfterSubmit(formCheckFunction) {
    $(document).on('submit', '#user-registration-form', async function (event) {
        event.preventDefault();

        // Supprimer le gestionnaire d'événement pour éviter une récursion infinie
        $(document).off('submit', '#user-registration-form');

        const formRegister = $('#user-registration-form');

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

		await formCheckFunction.checkOnSubmitAsync(formCheckFunction.isValidField(data), event, formRegister);
	});
}