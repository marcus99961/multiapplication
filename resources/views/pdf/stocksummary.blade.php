
<!DOCTYPE html>
<html>
<head>
    <title>This mail is from Case Management, Inya Lake Hotel</title>
    <style>
        body{font-family: Pyidaungsu;}
        table, th, td {
            border: 1px solid black;
            padding: 3px;
            border-collapse: collapse;
        }
        span {
            font-weight: bold;
        }
        .right {
        text-align: right;
        }
        .inProgress {
            color: orange;
        }
        .project {
            color: blue;

        }
        .grand {
            font-weight: bold;
        }
        .assign {
            color: violet;

        }
        .closed {
            color: gray;

        }
        p,.pending {
            color: red;
        }
        .room {
            color:darkblue;
        }
    </style>
</head>
<body>
    <h3>Daily Stock Summary</h3>
<table>
    <thead>
        <tr>                               
            <th rowspan="2">R R</th>
            <th rowspan="2">Supplier</th>
            <th colspan="2">Total Amount</th>
            <th colspan="2">FoodStore</th>
            <th colspan="2">BeverageStore</th>
            <th colspan="2">GeneralStore</th>
            <th colspan="2">EnegineerStore</th>
            <th colspan="2">EnergyStore</th>
            <th colspan="2">Linen Store</th>
           
           <tr>
                <th>USD</th>
                <th>MMK</th>
                <th>USD</th>
                <th>MMK</th>
                <th>USD</th>
                <th>MMK</th>
                <th>USD</th>
                <th>MMK</th>
                <th>USD</th>
                <th>MMK</th>
                <th>USD</th>
                <th>MMK</th>    
                <th>USD</th>
                <th>MMK</th>             

                </tr>
       
            
           
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $data)
        <tr>
           <td>{{ $data->invoice_no }} </td>
           <td>{{ $data->supplier->name }} </td>
         
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td>
          
        
          
           @if($data->location_id == '11')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td> 
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
           @endif
           @if($data->location_id == '1')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td>
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
           @endif
           @if($data->location_id == '12')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td> 
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
        
           @endif
           @if($data->location_id == '9')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td>
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
           @endif
           @if($data->location_id == '15')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td>
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
           @endif
           @if($data->location_id == '13')    
           <td class="right">{{ $data->total_usd }} </td>
           <td class="right">{{ $data->total_mmk }} </td>
           @else
           <td class="right">0</td>
           <td class="right">0</td>  
           @endif
           
                 
     
        </tr>
        
       
        @endforeach
      
        <tr class="grand">
            <td colspan="2">Grand Total</td>           
            
            @if($usd==null)
            <td class="right">0</td>
            @else
            <td class="right">{{ $usd->grand_usd }} </td>  
            @endif
            @if(!$mmk)
            <td class="right">0</td>
            @else
            <td class="right">{{ $mmk->grand_mmk }} </td>  
            @endif
          
            @if($loc[1]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[1]->usd }} </td>
           <td class="right">{{ $loc[1]->mmk }} </td>
           @endif
           @if($loc[2]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[2]->usd }} </td>
           <td class="right">{{ $loc[2]->mmk }} </td>
           @endif
           @if($loc[3]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[3]->usd }} </td>
           <td class="right">{{ $loc[3]->mmk }} </td>
           @endif
           @if($loc[4]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[4]->usd }} </td>
           <td class="right">{{ $loc[4]->mmk }} </td>
           @endif
           @if($loc[5]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[5]->usd }} </td>
           <td class="right">{{ $loc[5]->mmk }} </td>
           @endif
           @if($loc[6]==null)
           <td class="right">0</td>
           <td class="right">0 </td> 
           @else
           <td class="right">{{ $loc[6]->usd }} </td>
           <td class="right">{{ $loc[6]->mmk }} </td>
           @endif
          
           
        </tr>

       
    </tbody>
  
</table>
<p>#Please note that this report is automatically generated by Inventory System.</p>

</body>
</html>