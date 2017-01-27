<?php

class observer {
    /**
     * Redirect all events to this log manager, but only if this
     * log manager is actually used.
     *
     * @param \core\event\base $event
     */
    public static function create_user_django(\core\event\base $event) {
        error_log('My variable x is:');
    }
}
