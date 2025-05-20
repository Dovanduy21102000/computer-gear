/**
 * Slug Generator
 * Converts text into URL-friendly slugs
 */

function generateSlug(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, "-") // Replace spaces with -
        .replace(/&/g, "-and-") // Replace & with 'and'
        .replace(/[^\w\-]+/g, "") // Remove all non-word chars
        .replace(/\-\-+/g, "-") // Replace multiple - with single -
        .replace(/^-+/, "") // Trim - from start of text
        .replace(/-+$/, ""); // Trim - from end of text
}

// Add event listener to input fields that need slug generation
document.addEventListener("DOMContentLoaded", function () {
    const slugInputs = document.querySelectorAll("[data-slug-generator]");

    slugInputs.forEach(function (input) {
        const targetField = document.querySelector(input.dataset.slugTarget);
        if (!targetField) return;

        input.addEventListener("input", function () {
            targetField.value = generateSlug(this.value);
        });
    });
});
