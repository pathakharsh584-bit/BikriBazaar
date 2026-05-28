document.addEventListener('DOMContentLoaded', () => {

    const savedTheme = localStorage.getItem('theme');

    if(savedTheme === 'dark'){

        document.body.classList.add('dark');

    }

    const themeToggle = document.getElementById('themeToggle');

    if(themeToggle){

        if(savedTheme === 'dark'){

            themeToggle.innerHTML = '☀️';

        }

        themeToggle.addEventListener('click', () => {

            document.body.classList.toggle('dark');

            if(document.body.classList.contains('dark')){

                localStorage.setItem('theme', 'dark');

                themeToggle.innerHTML = '☀️';

            }

            else{

                localStorage.setItem('theme', 'light');

                themeToggle.innerHTML = '🌙';

            }

        });

    }

});