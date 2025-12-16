<x-filament-panels::page>
    {{-- Page content --}}
    @livewireStyles()
    @livewire('instructor.q-r-scanner.index')
    @livewire('instructor.q-r-scanner.logs')
    @livewireScripts()
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        const myAccount = @json(auth()->user()->id);
        console.log('my account id = ' + myAccount);

        Pusher.logToConsole = true;

        var pusher = new Pusher('e4faca1c135c89231d38', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('admin-channel');
        channel.bind('remaining-alert', function(data) {
            Swal.fire({
                icon: "warning",
                title: "Subscription Expiration Warning",
                text: data.message,
                showConfirmButton: false,
                timer: 4300
            });
        });
    </script>
</x-filament-panels::page>
