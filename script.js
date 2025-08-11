// script.js
const searchInput = document.getElementById('search');
const select = document.getElementById('dropdown-select');

searchInput.addEventListener('input', function () {
    const searchTerm = searchInput.value.toLowerCase();
    const options = select.getElementsByTagName('option');

    for (let i = 0; i < options.length; i++) {
        const optionText = options[i].textContent.toLowerCase();
        if (optionText.includes(searchTerm)) {
            options[i].style.display = 'block';
        } else {
            options[i].style.display = 'none';
        }
    }
    select.style.display = 'block';
});

// select.addEventListener('click', function () {
//     searchInput.value = ''; // Clear the search input when an option is selected
//     select.style.display = 'none'; // Hide the select again
// });
