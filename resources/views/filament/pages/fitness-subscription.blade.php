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
<script>
  // Target HTML element
  const html = document.documentElement;

  // Poll every 1 second
  setInterval(() => {
    const elem = document.querySelector(".fi"); // Your element

    if (!elem) return; // Exit if element not yet loaded

    // If element has "dark" class, enable Tailwind dark mode
    if (elem.classList.contains("dark")) {
      if (!html.classList.contains("dark")) {
        html.classList.add("dark");
        console.log("Dark mode activated");
      }
    } else {
      if (html.classList.contains("dark")) {
        html.classList.remove("dark");
        console.log("Dark mode deactivated");
      }
    }
  }, 1000);
</script>

</x-filament-panels::page>
