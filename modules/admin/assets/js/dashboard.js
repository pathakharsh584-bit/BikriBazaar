const ctx = document.getElementById("overviewChart");

if(ctx){

    new Chart(ctx, {

        type: "line",

        data: {

            labels: [

                "Mon",
                "Tue",
                "Wed",
                "Thu",
                "Fri",
                "Sat",
                "Sun"

            ],

            datasets: [

                {
                    label: "Users",

                    data: [120, 190, 170, 220, 260, 240, 300],

                    borderColor: "#5b7cff",

                    backgroundColor: "rgba(91,124,255,0.12)",

                    tension: 0.4,

                    fill: true,

                    pointRadius: 4,

                    pointBackgroundColor: "#5b7cff"
                },

                {
                    label: "Revenue",

                    data: [80, 140, 120, 180, 200, 190, 240],

                    borderColor: "#10b981",

                    backgroundColor: "rgba(16,185,129,0.08)",

                    tension: 0.4,

                    fill: true,

                    pointRadius: 4,

                    pointBackgroundColor: "#10b981"
                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: true,

                    labels: {

                        color: "#4b5563",

                        font: {
                            size: 13
                        }

                    }

                }

            },

            scales: {

                x: {

                    grid: {
                        display: false
                    },

                    ticks: {
                        color: "#9ca3af"
                    }

                },

                y: {

                    grid: {
                        color: "rgba(200,200,200,0.15)"
                    },

                    ticks: {
                        color: "#9ca3af"
                    }

                }

            }

        }

    });

}

function toggleGuideBox(){

    const guideBox =

    document.getElementById(
        'guideBox'
    );

    const guideMessages =

    document.getElementById(
        'guideMessages'
    );

    guideBox.classList.toggle(
        'active'
    );

    if(

        !guideMessages.dataset.loaded

    ){

        setTimeout(() => {

            guideMessages.innerHTML = `

                <div class="guide-msg">

                    👋 Welcome Admin!

                </div>

                <div class="guide-msg">

                    • Monitor reported ads regularly

                </div>

                <div class="guide-msg">

                    • Remove fake/scam products quickly

                </div>

                <div class="guide-msg">

                    • Review premium promotions daily

                </div>

                <div class="guide-msg">

                    • Keep categories clean & updated

                </div>

                <div class="guide-msg">

                    • Track user activities carefully

                </div>

            `;

        }, 1800);

        guideMessages.dataset.loaded = true;

    }

}