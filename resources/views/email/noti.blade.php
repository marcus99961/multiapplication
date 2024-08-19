<!DOCTYPE html>
<html>
<head>
    <title>This mail is from Case Management, Inya Lake Hotel</title>
    <style>
    table, th, td {
  border: 1px solid black;
  padding: 3px;
  margin-top: 7px;
  border-collapse: collapse;
}
</style>
</head>
<body>
<h3>Notification for new complaint</h3> </br>
Dear {{ $request['selectedDepartment'] }} team,</br>
I'm {{ $request['user_name'] }} from {{ $request['user_department']->name }}. Please find below for the newly created complaint. 
You can go and see http://ilhapp.ilh with Admin Network. </br>

<table>
                            <thead>
                            <tr>
                                
                                <th>Location</th>
                                <th>Defect</th>
                                <th>Source</th>
                                <th>Priority</th>
                                <th>Atten</th>
                                <th>Remark</th>
                                <th>By</th>
                                
                              
                             
           
          
                            </tr>
                            </thead>
                            <tbody>
                         
                            <tr>                               
                       
                        <td> @foreach($request['selectedRoom'] as $room)
                            {{$room}},
                            @endforeach
                        </td>
                       
                               
                                <td>{{$request['defect']}}</td>
                                <td>{{$request['source']}}</td>
                                <td>{{$request['priority']}}</td>
                                <td>{{$request['selectedDepartment']}}</td>
                                <td>{{$request['remark']}}</td>
                                <td>{{ $request['user_department']->name }} </td>
                                
                             
                               

                            </tr>
                       

                            </tbody>
                    </table>  
</body>
</html>