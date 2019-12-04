#!/usr/bin/env python

import json
from subprocess import run
from variables import php_releases

for php_release in php_releases:
    command = ["docker", "pull", php_release]

image = "michaelmcandrew/civicrm"
combos = json.load(open("combos.json"))

for key, combo in combos.items():
    # tags = {x for x in combo["tags"]}
    command = ["docker", "build", combo["dir"]]
    for tag in combo["tags"]:
        command.extend(["-t", f"{image}:{tag}"])
        run(command)

