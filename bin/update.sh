cd ..
git pull
./generate.py
./build.py
./publish.py
git add README.md
git commit -m 'Auto-updating generated files'
git push
