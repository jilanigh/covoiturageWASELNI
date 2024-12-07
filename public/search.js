// public/js/search.js

document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const resultsContainer = document.getElementById('results-container');

    searchForm.addEventListener('input', function () {
        const formData = new FormData(searchForm);
        const params = new URLSearchParams(formData).toString();

        fetch(`/trajet/search_ajax?${params}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(trajet => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.innerHTML = `
                            <strong>Departure:</strong> ${trajet.depart}<br>
                            <strong>Arrival:</strong> ${trajet.arrivee}<br>
                            <strong>Date:</strong> ${trajet.dateDepart}<br>
                            <strong>Price:</strong> ${trajet.prix}<br>
                            <strong>Available Seats:</strong> ${trajet.placeDispo}
                        `;
                        resultsContainer.appendChild(listItem);
                    });
                } else {
                    resultsContainer.innerHTML = '<p>No trips found for the given criteria.</p>';
                }
            });
    });
});