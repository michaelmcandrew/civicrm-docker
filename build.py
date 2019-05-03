#!/usr/bin/env python

import json
import subprocess

image = "michaelmcandrew/civicrm"
combos = json.load(open("combos.json"))

for key, combo in combos.items():
    # tags = {x for x in combo["tags"]}
    command = ["docker", "build", combo["dir"]]
    # command.append(["--no-cache=true"]) # TODO: When this is automated, force downloading the latest upstream builds
    for tag in combo["tags"]:w
        command.extend(["-t", f"{image}:{tag}"])
    subprocess.run(command)

