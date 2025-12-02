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

        // Function to check for class every 1 second
        setInterval(() => {
            const elem = document.querySelector(".fi"); // Target element with class 'fi'
            if (!elem) return;

            if (elem.classList.contains("dark")) {
                html.classList.add("dark"); // Activate Tailwind dark mode
            } else {
                html.classList.remove("dark"); // Deactivate dark mode
            }
        }, 1000); // 1000ms = 1 sec
    </script>

</x-filament-panels::page>
