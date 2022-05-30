<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <form action="/" method="post">
    @csrf
        <input type="text" name="p">
        <input type="submit" value="Authenticate">
    </form>
</body>
</html>