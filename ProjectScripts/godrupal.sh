#!/bin/bash
while getopts ":n:r:s:d:p:" optname
  do
    case "$optname" in
      "n")
        NAME=$OPTARG
        ;;
      "r")
        REPO_NAME=$OPTARG
        ;;
      "s")
        SAFE_NAME=$OPTARG
        ;;
      "d")
        DISPLAY_NAME=$OPTARG
        ;;
      "p")
        DB_PASS=$OPTARG
        ;;
      "?")
        echo "Unknown option $OPTARG"
        ;;
      ":")
        echo "No argument value for option $OPTARG"
        ;;
      *)
      # Should not occur
        echo "Unknown error while processing options"
        ;;
    esac
  done

if [ ! "$NAME" ] || [ ! "$REPO_NAME" ] || [ ! "$SAFE_NAME" ] || [ ! "$DISPLAY_NAME" ] || [ ! "$DB_PASS" ]
then
    echo "usage: godrupal.sh -n {app_name} -r {repo_name} -s {safe_name} -d {display_name} -p {db_pass}
    e.g. godrupal.sh -n pathlabsofark -r PathLabsOfArk -s pathlabsofark -d \"Pathology Labs of Arkansas\" -p \"KdjEhvieELp\"" 1>&2
    exit 1
fi

# Exit on error instead of continuing
set -e

if [ -d ../${REPO_NAME} ]
then
    while true; do
        read -p "Directory '"${REPO_NAME}"' exists. Continue with parameter replacement?" yn
        case $yn in
            [Yy]* ) echo Continuing; break;;
            [Nn]* ) exit;;
            * ) echo "Please answer yes or no.";;
        esac
    done
else
    ./createRepoFromTemplate.sh DrupalTemplate $REPO_NAME
fi

# Start echoing for these lines
set -x

# "|| true" to prevent error checking for these commands
./replaceInAll.sh "__name__" "$NAME" ../${REPO_NAME} || true
./replaceInAll.sh "__repo_name__" "$REPO_NAME" ../${REPO_NAME} || true
./replaceInAll.sh "__safe_name__" "$SAFE_NAME" ../${REPO_NAME} || true
./replaceInAll.sh "__display_name__" "$DISPLAY_NAME" ../${REPO_NAME} || true
./replaceInAll.sh "__db_pass__" "$DB_PASS" ../${REPO_NAME} || true
cd ../${REPO_NAME}
git mv db/__safe_name__.sql db/${SAFE_NAME}.sql
git commit -a -m "Updated string values"
git push
