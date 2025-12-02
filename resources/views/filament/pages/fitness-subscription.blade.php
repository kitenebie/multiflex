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
</x-filament-panels::page>
