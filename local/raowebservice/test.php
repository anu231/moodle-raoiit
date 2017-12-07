//////////////////// Rao Web Services - User-grievance Responses ///////////////////////////

    public static function get_user_grievance_response_parameters() {
        return new external_function_parameters(
            array()
        );
    }

    public static function get_user_grievance_response() {
        
        $grievance_list = get_grievance_list();
        //print_r($grievance_list);
        $greivance_id = array();
        foreach ($grievance_list as $key => $value) {
            $greivance_id[] = $grievance_list[$key]->id;
           
        }
        echo $greivance_id;
        exit;
        /*
        foreach ($grievance_list as $key => $value) {
            $greivance_response[] = get_greivance_response($value);
        }

        //print_r($greivance_response);
        //exit;
        return json_encode($greivance_response);
        $greivance_response = json_decode(json_encode($greivance_response), True);
        
        */
    }
   
    
    public static function get_user_grievance_response_returns() {
          return new external_value(PARAM_TEXT, 'Get User-grievance list');
    }