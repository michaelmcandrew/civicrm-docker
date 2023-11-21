#!/usr/bin/env python3

import json
from subprocess import run
from os import path

PROJECT_DIR = path.dirname(path.abspath(__file__))

image = "michaelmcandrew/civicrm"
combos = json.load(open(PROJECT_DIR + "/combos.json"))

for combo in combos.values():
    for tag in combo["tags"]:
        run(["docker", "push", f"{image}:{tag}"])

run(["docker", "push", "michaelmcandrew/civicrm-mysql:8.0"])
run(["docker", "push", "michaelmcandrew/civicrm-mysql:latest"])
