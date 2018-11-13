<?php

/*
    function to get all active centers
    arguments - $link_id(from connection function)
    return - $data (id & name of all active centres)  
    summary - Get all the active centres whose status=1 & returns associative array with centre_id & centre_name
*/
function get_active_centres($link_id){
    $sql="SELECT id,name FROM centreinfo WHERE status=1";
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