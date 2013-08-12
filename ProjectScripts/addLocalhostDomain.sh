#! /bin/bash

if [ ! "$1" ]
then
    echo "enter domain (e.g. \"pathlabsofark.localhost\")" 1>&2
    read DOMAIN
else
    DOMAIN=$1
fi

HOSTS_PATH=/etc/hosts
if [ -d /c/windows/ ]
then
    HOSTS_PATH=/c/windows/system32/drivers/etc/hosts
fi

DOMAIN_EXISTS=`grep $DOMAIN $HOSTS_PATH`
if [ "$DOMAIN_EXISTS" ]
then
    echo "Domain already exists" 1>&2
    exit 1
fi

echo "127.0.0.1	$DOMAIN" >> $HOSTS_PATH
