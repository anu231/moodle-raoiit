<?php

class block_rao_mocktest extends block_base {
    public function init() {
        $this->title = get_string('block_title', 'block_rao_mocktest');
    }
     Private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
		{   
			$str = '';
			$max = mb_strlen($keyspace, '8bit') - 1;
			for ($i = 0; $i < $length; ++$i) {
				$str .= $keyspace[random_int(0, $max)];
			}
			return $str;
		}
        
 	public function get_content(){
        global $USER, $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

		$secret = $CFG->secret_key;
		$nonce = $this->random_str(32);
		$st = $nonce.$USER->username;
		$hash_msg = hash_hmac('sha256',$st,$secret);
		$st_encoded = base64_encode($st);
		$hash_encoded = base64_encode($hash_msg);
		$nonce_enc = base64_encode($nonce);

        $mocktest_url='sig='.$st_encoded.'&hash='.$hash_encoded.'&nonce='.$nonce_enc;

        $this->content = new stdClass();
        $registration = new moodle_url($CFG->mocktest.$mocktest_url, array());
        $this->content->text = html_writer::link($registration,"Rao online Mock Test Registration");
        //$this->content->text .= '<br>'.$st;
        //$this->content->text .= '<br>'.$hash_msg;
        return $this->content;
    }


    public function instance_allow_multiple() {
          return false;
    }
    function has_config() {return true;}             
                  
}