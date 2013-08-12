if [ ! "$1" ] || [ ! "$2" ]
then
    echo "usage: createRepoFromTemplate.sh {template_name} {target_name}" 1>&2
    exit 1
fi

REPO_EXISTS=`ssh git@git.aristotle.net info | grep 'R   W.*'$2`
if [ "$REPO_EXISTS" ]
then
    echo "Repo already exists" 1>&2
    exit 1
fi

if [ -d ../$2 ]
then
    echo "Directory $2 exists. Please delete and try again" 1>&2
    exit 1
fi

if [ -d ../$2_template ]
then
    echo "Directory $2_template exists. Please delete and try again" 1>&2
    exit 1
fi

cd ../gitolite-admin
git pull
sed -i 's/Standard Repos/Standard Repos\n@standard_repos = '$2'/' conf/gitolite.conf
git commit -a -m "Added standard repo: $2" && git push
cd ..
git clone --depth 1 ssh://git@git.aristotle.net/$1 $2_template && \
    rm -rf $2_template/.git && \
    git clone ssh://git@git.aristotle.net/$2 && \
    mv $2_template/* $2_template/.??* $2 && \
    rm -rf $2_template
    cd $2 && \
    git add --all
    git commit -m "Initial commit from template." && \
    git push origin master:master
    #sed -i 's#git.aristotle.net/$1#git.aristotle.net/'$2'#' .git/config && \
