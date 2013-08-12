#!/bin/sh

date=`date "+%Y%m%dT%H%M%S"`
ssh ubuntudev.aristotle.net "tar -zcpf backups/redmine/redmine_git_$date.tgz /var/www/redmine_git"
scp ubuntudev.aristotle.net:backups/redmine/redmine_git_$date.tgz ./
