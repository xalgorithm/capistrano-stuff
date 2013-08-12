read -n1 -r -p "Run initial setup script? 
This will add host entries and create the local database. It assumes you have checked out the ProjectScripts repo as a peer of this repo.
Press ctrl-c to cancel, or any other key to continue."
cd db
start create_local_db.bat
cd ..

DIR="$( cd "$( dirname "$0" )" && pwd )"
DIR_FINAL_SEGMENT=${DIR##/*/}
SAFE_NAME="__safe_name__"
WEB_ROOT="$DIR_FINAL_SEGMENT/drupal"
echo "Creating localhost domain entry for $SAFE_NAME"
../ProjectScripts/addLocalhostDomain.sh "$SAFE_NAME.localhost"
sh ../ProjectScripts/addXamppVirtualHost.sh "$SAFE_NAME" "$WEB_ROOT"
read -n1 -r -p "Complete - Press any key to end"
