<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="locket-id" content="{{ $locket->id }}">
    <title>Locket</title>
    {{--
    due to the fact that talkify-tts has an old style code,
    it's not possible to use it in a vite way (i.e. importing it in resources/js/).
    Therefore, to use it mean to reference the file directly inside html's
    script tag as defined below.
    --}}
    <script src="{{ asset('vendor/talkify-tts/dist/talkify.min.js') }}"></script>
    @vite('resources/js/listenToQueueBroadcast.js')
    @vite('resources/css/locket-page.css')
</head>

<body>
    <div id="overlay">Click to Start</div>

    <nav class="locket-nav">
        <h3>Locket {{ $locket->code }}</h3>
    </nav>

    <main class="queue-display">
        <h2 class="queue-text">Calling for:</h2>
        <h1 id="queue-number" class="queue-number"></h1>
        <h2 class="room-text">To room <span id="room-number"></span></h2>
    </main>

</body>

</html>