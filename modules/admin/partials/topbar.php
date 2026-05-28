<div class="topbar">

    <div class="topbar-left">

        <button class="menu-toggle">

            <i class="fa-solid fa-bars"></i>

        </button>

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                placeholder="Search anything..."
            >

        </div>

    </div>

    <div class="topbar-right">

        <!-- GUIDE BUTTON -->

      <button
    class="topbar-icon"
    onclick="document.getElementById('guideBox').classList.toggle('active')">

    <i class="fa-solid fa-lightbulb"></i>

</button>

        <!-- PROFILE -->

        <div class="topbar-profile">

            <div class="profile-avatar">

                👨‍💼

            </div>

            <div class="profile-info">

                <h4>

                    Super Admin

                </h4>

                <p>

                    Administrator

                </p>

            </div>

            <i class="fa-solid fa-chevron-down"></i>

        </div>

    </div>

</div>
<div
    id="guideBox"
    class="guide-box">

    <div class="guide-header">

        💡 Admin Assistant

    </div>

    <div
        class="guide-messages"
        id="guideMessages">

        <div class="typing">

            <span></span>
            <span></span>
            <span></span>

        </div>

    </div>

</div>

<script>

setTimeout(() => {

    document.getElementById(
        'guideMessages'
    ).innerHTML = `

        <div class="guide-msg">

            👋 Welcome Admin!

        </div>

        <div class="guide-msg">

            • Monitor reported ads regularly

        </div>

        <div class="guide-msg">

            • Remove fake/scam ads quickly

        </div>

        <div class="guide-msg">

            • Review premium promotions daily

        </div>

        <div class="guide-msg">

            • Keep categories updated

        </div>

        <div class="guide-msg">

            • Track user activities carefully

        </div>

    `;

}, 1800);

</script>