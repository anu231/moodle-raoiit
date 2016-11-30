<?php
// The notice board block.
// It includes all the latest and greatest notices for the kickass students and
// badass faculties

class block_notice_board extends block_base {
    public function init(){
      $this->title = get_string('notice_board', 'block_notice_board');
    }
    public function get_content(){
      if($this->content != null){
        return $this->content;
      }
      $this->content = new stdClass;
      $this->content->text = '
      <h4> TODO </h4>
      <ul>
        <li>Learn about blocks</li>
        <li>Author a badass block</li>
        <li>Learn about modules</li>
        <li>Author a badass module</li>
      </ul>
      ';
      $this->content->footer = 'Done quiet beautifully, I must say!';

      return $this->content;
    }

}

?>