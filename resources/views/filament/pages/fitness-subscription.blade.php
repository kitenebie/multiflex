<x-filament-panels::page>
    {{-- Page content --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @livewire('member.pricing')
    @livewireScripts()
    <style>
        .fi-header {
            display: none !important;
        }
    </style>
    <script>
        const html = document.documentElement;

        // On page load: set initial dark mode to prevent FOUC
        html.classList.toggle(
            "dark",
            localStorage.theme === "dark" ||
            (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
        );

        // Function to activate/deactivate dark mode based on .fi.dark
        function syncDarkModeFromFi() {
            const elem = document.querySelector(".fi");
            if (!elem) return;

            if (elem.classList.contains("dark")) {
                html.classList.add("dark");
                localStorage.theme = "dark"; // save preference
            } else {
                html.classList.remove("dark");
                localStorage.theme = "light"; // save preference
            }
        }

        // Poll every 1 second
        setInterval(syncDarkModeFromFi, 1000);

        // Optional: Manual toggle button
        const toggleBtn = document.getElementById("themeToggle");
        if (toggleBtn) {
            toggleBtn.addEventListener("click", () => {
                html.classList.toggle("dark");
                if (html.classList.contains("dark")) {
                    localStorage.theme = "dark";
                } else {
                    localStorage.theme = "light";
                }
            });
        }
    </script>

</x-filament-panels::page>
