#!/bin/bash

cd $DIR/..

git pull
./generate.py
./build.py
./publish.py
git commit -am 'Auto-updating generated files'
git push
