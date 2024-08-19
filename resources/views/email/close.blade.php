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

<h3>Complaint ID - {{$request['id']}} is closed</h3>
This complaint is closed by {{ $request['byName'] }} on {{ $closedate }}. 

</body>
</html>