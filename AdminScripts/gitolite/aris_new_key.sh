#!/bin/bash
if [ ! -f /usr/bin/curl ]; then
    echo "curl is required. Run 'sudo apt-get curl'."
    exit
fi
cd ~/
COPYDATE=`date +%Y%m%d-%H%M%S`
if [[ -n "$1" ]]
then
    FINALUSER="$1"
elif [[ -n "$USER" ]]
then
    FINALUSER="$USER"
else
    FINALUSER="$USERNAME"
fi

test -z "$FINALUSER" && echo "Could not establish user name; try passing as argument" && exit

if [[ -f .ssh/id_rsa ]]
then
	mv .ssh/id_rsa .ssh/id_rsa_$COPYDATE
	mv .ssh/id_rsa.pub .ssh/id_rsa_$COPYDATE.pub
fi

ssh-keygen -t rsa -C "$FINALUSER@aristotle.net"
curl -T .ssh/id_rsa ftp://ubuntudev.aristotle.net/ssh_keys/$FINALUSER.private --user anonymous:
curl -T .ssh/id_rsa.pub ftp://ubuntudev.aristotle.net/ssh_keys/$FINALUSER.pub --user anonymous:
echo Please tell a git admin to add your key

