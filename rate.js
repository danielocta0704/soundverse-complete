const stars1 = document.querySelectorAll('.star');
let selectedRating1 = 0;

stars1.forEach(star => {
    star.addEventListener('click', function() {
        selectedRating1 = parseInt(this.getAttribute('data-value'));

        stars1.forEach(star => star.classList.remove('active'));

        for (let i = 0; i < selectedRating1; i++) {
            stars1[i].classList.add('active');
        }
    });
});

const stars2 = document.querySelectorAll('.star2');
let selectedRating2 = 0;

stars2.forEach(star => {
    star.addEventListener('click', function() {
        selectedRating2 = parseInt(this.getAttribute('data-value'));

        stars2.forEach(star => star.classList.remove('active'));

        for (let i = 0; i < selectedRating2; i++) {
            stars2[i].classList.add('active');
        }
    });
});

document.getElementById('submitRating').addEventListener('click', function() {
    if (selectedRating1 > 0 || selectedRating2 > 0) {
        alert(`You rated "Imagine" ${selectedRating1} stars and "Selfless" ${selectedRating2} stars!`);
    } else {
        alert('Please select a rating for at least one song!');
    }
});