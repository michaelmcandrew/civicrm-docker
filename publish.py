#!/usr/bin/env python

import json
from subprocess import run

image = "michaelmcandrew/civicrm"
combos = json.load(open("combos.json"))

for combo in combos.values():
    for tag in combo["tags"]:
        run(["docker", "push", f"{image}:{tag}"])

run(["docker", "push", "michaelmcandrew/civicrm-mysql:5.7"])
run(["docker", "push", "michaelmcandrew/civicrm-mysql:latest"])
