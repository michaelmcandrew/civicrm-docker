#!/usr/bin/env python
import itertools
import json
from subprocess import run
from variables import php_releases

for php_release in php_releases:
    command = ["docker", "pull", f"php:{php_release}"]
    run(command)

image = "michaelmcandrew/civicrm"
combos = json.load(open("combos.json"))

for key, combo in combos.items():
    command = ["docker", "build", combo["dir"]]
    command += list(
        itertools.chain.from_iterable(("-t", f"{image}:{tag}") for tag in combo["tags"])
    )
    run(command)
