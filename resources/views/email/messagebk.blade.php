<!DOCTYPE html>
<html>
<head>
    <title>This mail is from Case Management, Inya Lake Hotel</title>
    <style>
        table, th, td {
            border: 1px solid black;
            padding: 3px;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

<h3>You got a message from {{$request->sender->name}}, regarding about the complaint ID - {{ $request->complaint_id }} </h3>
"{{ $request->name }}"
</body>
</html>
