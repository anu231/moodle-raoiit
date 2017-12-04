#!/bin/bash
echo 'PUSHING TO SERVER 1'
ssh mood1 'sudo -u www-data /var/www/moodle/reload_git.sh'
echo 'PUSHING TO SERVER 2'
ssh mood2 'sudo -u www-data /var/www/moodle/reload_git.sh'