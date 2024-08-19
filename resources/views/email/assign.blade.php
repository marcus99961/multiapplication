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

<h3>Notification for {{ $request['status'] }} status of complaintID - {{$request['id']}}</h3>
Dear team,</br>
I'm {{ $request['user_name'] }}. Please find below for the updated status. 
You can go and see http://ilhapp.ilh with Admin Network. </br></br>
<table>
                            <thead>
                            <tr>
                                
                            @if($request['status']=='assign')
                                <th>Defect</th>        
                                <th>Status</th>
                                <th>Assigned</th>
                                <th>Mobile</th>
                              
                            @else 
                            <th>Defect</th> 
                            <th>Status</th>
                            <th>Action</th>
                            @endif 
                             
           
          
                            </tr>
                            </thead>
                            <tbody>
                         
                            <tr>                               
                       
                      
                       
                                @if($request['status']=='assign')  
                                <td>{{ $request['defect']}}</td>
                                <td>{{$request['status']}}</td>
                                <td>{{$request['member_name']}}</td>
                                <td>{{$request['member_mobile']}}</td>
                                @else
                                <td>{{ $request['defect'] }}</td>
                                <td>{{ $request['status'] }}</td>
                                <td>{{$request['action']}}</td>
                                @endif
                               
                               
                                
                             
                               

                            </tr>
                       

                            </tbody>
                    </table>  
</body>
</html>