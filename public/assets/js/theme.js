document.addEventListener("DOMContentLoaded", () => {
    
    const navLinks = document.querySelector(".nav-links");

    if (navLinks) {
        const toggleBtn = document.createElement("button");
        toggleBtn.id = "themeToggle";
        toggleBtn.className = "theme-toggle-btn";
        toggleBtn.setAttribute("aria-label", "Toggle dark mode");
        toggleBtn.innerText = "🌙 Dark";

        navLinks.appendChild(toggleBtn);

        const currentTheme = localStorage.getItem("theme") || "light";
        document.documentElement.setAttribute("data-theme", currentTheme);
        updateToggleText(currentTheme);

        toggleBtn.addEventListener("click", () => {
            const theme = document.documentElement.getAttribute("data-theme");
            const newTheme = theme === "dark" ? "light" : "dark";

            document.documentElement.setAttribute("data-theme", newTheme);
            localStorage.setItem("theme", newTheme);
            updateToggleText(newTheme);
        });

        function updateToggleText(theme) {
            toggleBtn.innerHTML = theme === "dark" ? "☀️ Light" : "🌙 Dark";
        }
    }
});