<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @can('accept patient')
        <div>
            <h1>Current Patient</h1>
            <h2>Jane Doe</h2>
            <ul>
                <li>Female</li>
                <li>24 Years Old</li>
            </ul>
        </div>
    @endcan
</body>
</html>