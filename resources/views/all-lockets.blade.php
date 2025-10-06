<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Lockets</title>
</head>

<body>
    <div style="display: flex; flex-wrap: wrap; height: 95vh; width: 100vw; overflow-y: scroll;">
        @foreach ($locketIds as $id)
            <iframe src="{{ route('locket', ["id" => $id]) }}" frameborder="0" style="width: 49vw;">
            </iframe>
        @endforeach
    </div>
</body>

</html>