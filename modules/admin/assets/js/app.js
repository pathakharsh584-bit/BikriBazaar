const counters = document.querySelectorAll(".counter");

counters.forEach(counter => {

    const updateCounter = () => {

        const target = +counter.getAttribute("data-target");

        const current = +counter.innerText.replace(/,/g,'');

        const increment = target / 80;

        if(current < target){

            counter.innerText = Math.ceil(current + increment).toLocaleString();

            setTimeout(updateCounter,20);

        }else{

            if(counter.classList.contains("revenue-counter")){

                counter.innerText = "₹" + target.toLocaleString();

            }else{

                counter.innerText = target.toLocaleString();

            }

        }

    };

    updateCounter();

});
