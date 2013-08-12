grep -Irl "$1" "$3" | grep -v "\.git" | xargs sed -i 's/'"$1"'/'"$2"'/g'
