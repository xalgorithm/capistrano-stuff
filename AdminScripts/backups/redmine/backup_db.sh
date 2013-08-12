#!/bin/sh

date=`date "+%Y%m%dT%H%M%S"`
mysqldump -u jfendley -p -h ubuntudev.aristotle.net redmine_default --add-drop-table --skip-extended-insert > redmine_default_$date.sql
