#!/bin/sh

date=`date "+%Y%m%dT%H%M%S"`
mysqldump -u jfendley -p -h ubuntudev.aristotle.net redmine_default --add-drop-table --skip-extended-insert > redmine_default_$date.sql
ssh ubuntudev.aristotle.net "tar -zcpf backups/redmine/redmine_git_$date.tgz /var/www/redmine_git"
scp ubuntudev.aristotle.net:backups/redmine/redmine_git_$date.tgz ./
