#!/bin/sh

set -e
cd `php -r "echo dirname(realpath('$0'));"`

echo ">>> Cleanup example"
if [ -d web/photos ]; then
    rm -rf web/photos
fi

mkdir -p web/photos
cd web/photos
mkdir "Paris"
mkdir "San Francisco"

cd Paris
mkdir 1
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/f/f3/Grand_paris_express.svg/741px-Grand_paris_express.svg.png
cd 1
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Paris_Night.jpg/800px-Paris_Night.jpg
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/1/1f/Eiffel_Tower_and_general_view_of_the_grounds%2C_Exposition_Universal%2C_1900%2C_Paris%2C_France_2.jpg/800px-Eiffel_Tower_and_general_view_of_the_grounds%2C_Exposition_Universal%2C_1900%2C_Paris%2C_France_2.jpg
cd ..
mkdir 2
cd 2
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Hotel_de_Ville_Paris_Wikimedia_Commons.jpg/1024px-Hotel_de_Ville_Paris_Wikimedia_Commons.jpg
cd ../..

cd "San Francisco"
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/d/da/SF_From_Marin_Highlands3.jpg/800px-SF_From_Marin_Highlands3.jpg
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Castro_Rainbow_Flag.jpg/450px-Castro_Rainbow_Flag.jpg
wget -b http://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/San_Francisco_City_Bus.JPG/450px-San_Francisco_City_Bus.JPG
cd ../../..
