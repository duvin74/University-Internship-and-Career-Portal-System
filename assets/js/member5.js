/* CAREERCONNECT - MEMBER 5 JAVASCRIPT */

document.addEventListener("DOMContentLoaded", function () {

    const applicationForm = document.querySelector(
        '.m5-form-wrapper form[action="submit-application.php"]'
    );

    if (applicationForm) {

        applicationForm.addEventListener("submit", function (event) {

            const confirmCheckbox = applicationForm.querySelector(
                'input[name="confirm"]'
            );

            if (!confirmCheckbox.checked) {
                event.preventDefault();
                alert("Please confirm before submitting your application!");
                return;
            }

            const userConfirmed = confirm(
                "Are you sure you want to submit this internship application?"
            );

            if (!userConfirmed) {
                event.preventDefault();
                return;
            }

            alert("Your application is being submitted!");

        });

    }

});