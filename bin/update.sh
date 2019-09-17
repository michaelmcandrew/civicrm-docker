#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

cd $DIR/..

git pull
./generate.py
./build.py
./publish.py
git commit -am 'Auto-updating generated files'
git push
