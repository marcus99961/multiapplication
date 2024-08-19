<!-- <!DOCTYPE html>
<html>
<head>
    <title>How To Send Mail Using Queue In Laravel 9 - Techsolutionstuff</title>
</head>
<body>

<center>
    <h2>
        <a href="https://techsolutionstuff.com">Visit Our Website : Techsolutionstuff</a>
    </h2>
</center>

<p>Hello,</p>

<p>This is a test mail. This mail send using queue listen in laravel 9.</p>

<strong>Thanks & Regards.</strong>

</body>
</html> -->
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

<h3>You got a message from {{$request['sender']->name}}, regarding about the complaint ID - {{ $request['complaint_id'] }} </h3>
"{{ $request['name'] }}"
</body>
</html>

