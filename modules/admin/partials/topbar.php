<style>
    /* Topbar styles – profile area now dark (matching sidebar) */
    .topbar {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0px 0px 10px #949da9;
         border: 1px solid #caced7;
    }
    .topbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .menu-toggle {
        background: none;
        border: none;
        font-size: 1.3rem;
        color: #1a3fc4;
        cursor: pointer;
        padding: 0.4rem;
        border-radius: 8px;
        transition: background 0.2s;
    }
    .menu-toggle:hover {
        background: #f4f7ff;
    }
    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 40px;
        padding: 0.4rem 1rem;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .search-box i {
        color: #6b7280;
        font-size: 0.9rem;
    }
    .search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 0.85rem;
        width: 200px;
    }
    .search-box input:focus {
        width: 260px;
    }
    .search-box:focus-within {
        border-color: #1a3fc4;
        box-shadow: 0 0 0 2px rgba(26,63,196,0.1);
    }
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .topbar-icon {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 40px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: #1a3fc4;
        font-size: 1.1rem;
    }
    .topbar-icon:hover {
        background: #eef2ff;
        border-color: #1a3fc4;
        transform: translateY(-1px);
    }
    /* Profile area – dark background (like sidebar) */
    .topbar-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #fff;        /* dark slate */
        padding: 0.4rem 1rem 0.4rem 0.8rem;
        border-radius: 40px;
        cursor: pointer;
        transition: background 0.2s;
        border: none;
    }
    .topbar-profile:hover {
        background: #fff;
    }
    .profile-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
    }
    .profile-info h4 {
        font-size: 0.85rem;
        font-weight: 700;
        color: #111827;             /* white text */
        margin: 0;
        line-height: 1.2;
    }
    .profile-info p {
        font-size: 0.7rem;
        color: #4b5563;             /* light grey-white */
        margin: 0;
    }
    .topbar-profile i.fa-chevron-down {
        color: #94a3b8;
        font-size: 0.7rem;
    }

    /* Guide box – unchanged */
    .guide-box {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s;
        transform: translateY(120%);
        opacity: 0;
        visibility: hidden;
        z-index: 1000;
    }
    .guide-box.active {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
    }
    .guide-header {
        background: linear-gradient(95deg, #1a3fc4, #0ea5a0);
        color: white;
        padding: 0.75rem 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .guide-messages {
        padding: 1rem;
        max-height: 300px;
        overflow-y: auto;
        font-size: 0.85rem;
        color: #1a1a2e;
    }
    .guide-msg {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eef2ff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .guide-msg:last-child {
        border-bottom: none;
    }
    .typing {
        display: flex;
        gap: 4px;
        padding: 0.5rem;
    }
    .typing span {
        width: 8px;
        height: 8px;
        background: #1a3fc4;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }
    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    @media (max-width: 768px) {
        .topbar { padding: 0.75rem 1rem; }
        .search-box input { width: 140px; }
        .search-box input:focus { width: 180px; }
        .profile-info { display: none; }
        .topbar-profile { padding: 0.4rem; }
        .guide-box { width: 280px; right: 10px; bottom: 10px; }
    }
</style>

<div class="topbar">

    <div class="topbar-left">

        <button class="menu-toggle" id="menuToggleBtn">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search anything...">
        </div>

    </div>

    <div class="topbar-right">

        <!-- GUIDE BUTTON -->
        <button class="topbar-icon" id="guideToggleBtn" onclick="document.getElementById('guideBox').classList.toggle('active')">
            <i class="fa-solid fa-lightbulb"></i>
        </button>

        <!-- PROFILE -->
        <div class="topbar-profile">
            <div class="profile-avatar">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="profile-info">
                <h4>Super Admin</h4>
                <p>Administrator</p>
            </div>
            <i class="fa-solid fa-chevron-down"></i>
        </div>

    </div>

</div>

<div id="guideBox" class="guide-box">
    <div class="guide-header">
        <i class="fa-solid fa-lightbulb"></i> Admin Assistant
    </div>
    <div class="guide-messages" id="guideMessages">
        <div class="typing">
            <span></span><span></span><span></span>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        document.getElementById('guideMessages').innerHTML = `
            <div class="guide-msg"><i class="fa-solid fa-chart-line" style="color:#1a3fc4;"></i> Monitor reported ads regularly</div>
            <div class="guide-msg"><i class="fa-solid fa-trash-can" style="color:#ef4444;"></i> Remove fake/scam ads quickly</div>
            <div class="guide-msg"><i class="fa-solid fa-crown" style="color:#f59e0b;"></i> Review premium promotions daily</div>
            <div class="guide-msg"><i class="fa-solid fa-layer-group" style="color:#0ea5a0;"></i> Keep categories updated</div>
            <div class="guide-msg"><i class="fa-solid fa-clock-rotate-left" style="color:#6b7280;"></i> Track user activities carefully</div>
        `;
    }, 1800);
</script>