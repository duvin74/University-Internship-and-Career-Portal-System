/* CAREERCONNECT - MEMBER 4 JAVASCRIPT */

document.addEventListener("DOMContentLoaded", function () {

    // Internship Search Validation
    const searchForm = document.querySelector(".m4-search-form");

    if (searchForm) {

        searchForm.addEventListener("submit", function (event) {

            const searchInput = searchForm.querySelector('[name="search"]');

            if (!searchInput.value.trim()) {
                event.preventDefault();
                alert("Please enter a keyword to search for internships!");
                searchInput.focus();
                return;
            }

            alert("Searching for internships...");
        });

    }

    // Smooth Scroll
    const exploreButton = document.querySelector('.m4-hero a[href="#internship-list"]');

    if (exploreButton) {
        exploreButton.addEventListener("click", function (event) {
            event.preventDefault();

            const target = document.querySelector("#internship-list");

            if (target) {
                target.scrollIntoView({ behavior: "smooth" });
            }
        });
    }

});