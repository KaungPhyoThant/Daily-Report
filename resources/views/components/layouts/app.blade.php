<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        {{ $slot }}

        @livewire('notifications')

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let userId = {{ auth()->id() }};
        let notificationCount = 0;
        let originalTitle = document.title;

        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        var channel = pusher.subscribe("private-notifications." + userId);
        channel.bind("NewNotification", function (data) {
            console.log("New notification received:", data); // Debugging
            notificationCount = data.count;
            updateTabTitle();
        });

        function updateTabTitle() {
            if (notificationCount > 0) {
                document.title = `(${notificationCount}) New Notifications - ${originalTitle}`;
            } else {
                document.title = originalTitle;
            }
        }
    });
</script>
