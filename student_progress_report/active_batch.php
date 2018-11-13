<?php

/*
    function to get all active batches
    arguments - $link_id(from connection function)
    return - $data (id & name of all active batches)  
    summary - Get all the active batches whose status=1 & returns associative array with branch_id & branch_name
*/
function get_active_batches($link_id){
    $sql="SELECT id,name FROM ttbatches WHERE  status=1 AND targetyear=2019 AND (batch=2)";
    //$sql="SELECT id,name FROM ttbatches WHERE status=1 AND id IN(900,881,922,926)";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0){
        while ($row=mysqli_fetch_assoc($result)){
            $data[$row['id']] = $row['name'];
        }
    }
    return $data;        
}


?>