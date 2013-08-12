echo ${PWD##*/} >> /var/www/billing
du -h --max-depth=1 -X ./excludes >> /var/www/billing
