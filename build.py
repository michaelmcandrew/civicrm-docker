#!/usr/bin/env python3

import json
from subprocess import run
from variables import php_releases
from os import path

PROJECT_DIR = path.dirname(path.abspath(__file__))


# better to 'cache' here than via `docker build --no-cache`
for php_release in php_releases:
    command = ["docker", "pull", php_release]

image = "michaelmcandrew/civicrm"
combos = json.load(open(PROJECT_DIR + "/combos.json"))

for key, combo in combos.items():
    command = [
        "docker",
        "build",
        "--platform",
        "linux/arm64",
        PROJECT_DIR + "/" + combo["dir"],
    ]
    for tag in combo["tags"]:
        command.extend(["--tag", f"{image}:{tag}"])
    run(command)

run(["docker", "pull", "mysql:8.0"])
run(
    [
        "docker",
        "build",
        "--platform",
        "linux/arm64",
        PROJECT_DIR + "/" + "mysql",
        "--tag",
        "michaelmcandrew/civicrm-mysql:8.0",
        "--tag",
        "michaelmcandrew/civicrm-mysql:latest",
    ]
)
