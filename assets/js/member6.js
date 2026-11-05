/* CAREERCONNECT - MEMBER 6 ADMIN JAVASCRIPT */

document.addEventListener("DOMContentLoaded", function () {

    const deleteForms = document.querySelectorAll(".m6-confirm");

    deleteForms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const message = form.dataset.confirmMessage ||
                "Are you sure you want to delete this record?";

            const answer = confirm(message);

            if (!answer) {
                event.preventDefault();
            }

        });

    });

});