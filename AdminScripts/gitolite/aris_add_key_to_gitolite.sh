#!/bin/bash
test -z "$1" && echo "Please enter a username" && exit
if [ ! -f /usr/bin/curl ]; then
    echo "curl is required. Run 'sudo apt-get curl'."
    exit
fi
if [ ! -d ../../gitolite-admin ]; then
    echo "Expected gitolite-admin repository to be a sibling of AdminScripts"
    exit
fi
cd ../../gitolite-admin

# Exit on error instead of continuing
set -e

git pull
cd keydir
curl ftp://ubuntudev.aristotle.net/ssh_keys/$1.pub -o $1.pub --user anonymous:
pwd
git add $1.pub
git commit -m "Added $1"
git push

