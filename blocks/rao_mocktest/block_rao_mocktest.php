<?php
class block_rao_mocktest extends block_list {
    public function init() {
        $paper_name = get_config('rao_mocktest','rao_papername');
        $this->title = $paper_name;
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
        $paper_name = get_config('rao_mocktest','rao_papername');
        $raotest_url = get_config('rao_mocktest','raotest_url');
		$secret = $CFG->secret_key;
		$nonce = $this->random_str(32);
		$st = $nonce.$USER->username;
		$hash_msg = hash_hmac('sha256',$st,$secret);
		$st_encoded = base64_encode($st);
		$hash_encoded = base64_encode($hash_msg);
		$nonce_enc = base64_encode($nonce);
        $mocktest_url='sig='.$st_encoded.'&hash='.$hash_encoded.'&nonce='.$nonce_enc;
        $this->content = new stdClass();
        $this->content->items = array();
        $registration = new moodle_url($raotest_url.$mocktest_url, array());
        $this->content->items[] = html_writer::link($registration,$paper_name." Link");
        return $this->content;
    }

    public function instance_allow_multiple() {
          return true;
    }
    function has_config() {
        return true;
    }          
                  
}

