/*For the left to right tranformation*/
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        console.log(entry);

        if(entry.isIntersecting){
            entry.target.classList.add('show');
        }
        else{
            entry.target.classList.remove('show');
        }
    });
});

/*For the right to left tranformation*/
const hiddenEliment = document.querySelectorAll('.hidden');
hiddenEliment.forEach((el) => observer.observe(el));

const observers = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        console.log(entry);

        if(entry.isIntersecting){
            entry.target.classList.add('show');
        }
        else{
            entry.target.classList.remove('show');
        }
    });
});

const hiddenEliments = document.querySelectorAll('.right');
hiddenEliments.forEach((el) => observer.observe(el));