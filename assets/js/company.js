/* CAREERCONNECT - MEMBER 3 JAVASCRIPT */

document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector(".m3-form-box form");

    if (form) {

        form.addEventListener("submit", function (event) {

            const name = form.querySelector('[name="name"]');
            const email = form.querySelector('[name="email"]');
            const company = form.querySelector('[name="company_name"]');
            const website = form.querySelector('[name="website"]');
            const password = form.querySelector('[name="password"]');
            const confirmPassword = form.querySelector('[name="confirm_password"]');

            if (!name.value.trim() || !email.value.trim() || !company.value.trim()) {
                event.preventDefault();
                alert("Please fill in all required fields!");
                return;
            }

            if (password.value.length < 8) {
                event.preventDefault();
                alert("Password must contain at least 8 characters!");
                password.focus();
                return;
            }

            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                alert("Passwords do not match!");
                confirmPassword.focus();
                return;
            }

            if (website.value.trim() !== "") {
                try {
                    const url = new URL(website.value);
                    if (url.protocol !== "http:" && url.protocol !== "https:") {
                        throw new Error("Invalid URL");
                    }
                } catch {
                    event.preventDefault();
                    alert("Please enter a valid website URL starting with https://");
                    website.focus();
                    return;
                }
            }

        });

    }

});