<?php


class raobooklet implements renderable {
    public $raobooklet;

    public function __construct(stdclass $rb) {
        $this->raobooklet = $rb;
        $this->name = $rb->name;
        $this->standard = $rb->standard;
        $this->description = $rb->description;
        $this->subject = $rb->subject;
        $this->topics = $rb->topics;
        $this->instance = $rb->instance;
        $this->downloadlink = $rb->link;
        $this->back_link = $rb->back_link;
    }
}

class raobooklet_feedback implements renderable {
    public function __construct(stdclass $rbf) {
        $this->id = $rbf->id;
        $this->rating = $rbf->rating;
        $this->comment = $rbf->comment;
        $this->timestamp = date('l jS \of F Y h:i:s A', $rbf->timecreated);
        $this->updatelink = $rbf->updatelink;
    }
}


class mod_raobooklet_renderer extends plugin_renderer_base {
    var $template_raobooklet = "mod_raobooklet/raobooklet";
    var $template_raobooklet_feedback = "mod_raobooklet/raobooklet_feedback";
    var $options = "";

    // Booklet information (view.php)
    public function raobooklet(stdclass $rb) {
        $booklet = new raobooklet($rb);
        return $this->render($booklet);
    }
    protected function render_raobooklet(raobooklet $context) {
        return $this->render_from_template($this->template_raobooklet, $context);
    }

    // Booklet feedback form (view.php)
    public function raobooklet_feedback(stdclass $rbf) {
        $raobooklet_feedback = new raobooklet_feedback($rbf);
        return $this->render($raobooklet_feedback);
    }
    protected function render_raobooklet_feedback(raobooklet_feedback $context) {
        return $this->render_from_template($this->template_raobooklet_feedback, $context);
    }

}
