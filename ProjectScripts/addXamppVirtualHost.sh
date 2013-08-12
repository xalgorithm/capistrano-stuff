#!/bin/bash
cd $( dirname "$0" )
if [ ! "$1" ]
then
    echo "usage: addXamppVirtualHost.sh {site_name} {path under root}
        e.g. addXamppVirtualHost.sh pathlabsofark \"PathologyLabsOfArkansas/drupal\"" 1>&2
    exit 1
fi

VHOST_EXISTS=`grep $1'.localhost' ../../apache/conf/extra/httpd-vhosts.conf`
if [ "$VHOST_EXISTS" ]
then
    echo "Virtual Host entry already exists; overwrite? (yes|no)" 1>&2
    read OVERWRITE

    if [ "$OVERWRITE" == "yes" ]
    then
        sed -i s#$1.localhost#$1_disabled.localhost#g ../../apache/conf/extra/httpd-vhosts.conf
    else
        echo "No action taken."
        exit 1
    fi
fi

echo "Creating Virtual Host entry for $1.localhost pointing to $2"

sed s#__NAME__#$1#g < xamppVirtualHostTemplate.txt | sed s#__PATH__#$2#g >> ../../apache/conf/extra/httpd-vhosts.conf
echo "New virtual host added - please restart apache"
