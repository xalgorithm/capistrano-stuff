#!/bin/bash
# script to send simple email
#first erase the email file, it contains old data
rm -Rf /var/www/email.txt
touch /var/www/email.txt
# email subject
SUBJECT="Gaia Billing Summary"
# Email To ?
EMAIL="v.flesouras@aristotle.net"
# Email text/message
EMAILMESSAGE="/var/www/email.txt"
echo "Below are the adjusted folder size values" >>$EMAILMESSAGE
cat /var/www/billing >> /var/www/email.txt
# send an email using /bin/mail
/bin/mail -s "$SUBJECT" "$EMAIL" < $EMAILMESSAGE
