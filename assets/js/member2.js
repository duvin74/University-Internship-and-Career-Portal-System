/* CAREERCONNECT - MEMBER 2 JAVASCRIPT */

document.addEventListener("DOMContentLoaded", function () {

    const registerForm = document.querySelector(".m2-form-card form");

    if (registerForm) {

        registerForm.addEventListener("submit", function (event) {

            const name = registerForm.querySelector('[name="name"]');
            const email = registerForm.querySelector('[name="email"]');
            const password = registerForm.querySelector('[name="password"]');
            const confirmPassword = registerForm.querySelector('[name="confirm_password"]');
            const university = registerForm.querySelector('[name="university"]');
            const course = registerForm.querySelector('[name="course"]');

            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                alert("Passwords do not match!");
                confirmPassword.focus();
                return;
            }

            if (password.value.length < 8) {
                event.preventDefault();
                alert("Password must contain at least 8 characters!");
                password.focus();
                return;
            }

            if (!name.value.trim() || !email.value.trim() ||
                !university.value.trim() || !course.value.trim()) {
                event.preventDefault();
                alert("Please fill in all required fields!");
                return;
            }

        });

    }

});