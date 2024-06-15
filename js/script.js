// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {

    // Example of adding an event listener to the form submission
    var searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }

    // Function to perform search
    function performSearch() {
        var query = document.getElementById('searchInput').value.toLowerCase();
        var bookList = document.getElementById('bookList');
        var books = bookList.getElementsByTagName('li');

        for (var i = 0; i < books.length; i++) {
            var bookTitle = books[i].textContent || books[i].innerText;
            if (bookTitle.toLowerCase().indexOf(query) > -1) {
                books[i].style.display = '';
            } else {
                books[i].style.display = 'none';
            }
        }
    }

    // Example of adding event listener to rating stars
    var ratingStars = document.querySelectorAll('.rating-star');
    ratingStars.forEach(function(star) {
        star.addEventListener('click', function() {
            var rating = this.getAttribute('data-rating');
            document.getElementById('ratingInput').value = rating;
            highlightStars(rating);
        });
    });

    function highlightStars(rating) {
        ratingStars.forEach(function(star) {
            if (star.getAttribute('data-rating') <= rating) {
                star.classList.add('highlighted');
            } else {
                star.classList.remove('highlighted');
            }
        });
    }
});
