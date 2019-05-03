#!/usr/bin/env python

import os
from subprocess import run
import jinja2
import itertools
import json

# Define releases and variants
latest_civicrm_release = os.popen(
    "curl -s https://latest.civicrm.org/stable.php"
).read()

civi_releases = [latest_civicrm_release]
cms_variants = ["drupal", "wordpress", "backdrop"]
php_releases = ["7.3", "7.2", "7.1", "7.0", "5.6"]
defaults = {"civi": latest_civicrm_release, "cms": "drupal", "php": "7.2"}

# Generate combinations
combos = {}
for civi, cms, php in itertools.product(civi_releases, cms_variants, php_releases):
    major = civi.split(".")[0]
    key = f"{civi}/{cms}/{php}"
    combos[key] = {
        "tags": [],
        "variables": {"civi": civi, "cms": cms, "php": php},
        "dir": f"{major}/{cms}/{php}",
    }


# Generate tags
tags = []
for civi, cms, php in itertools.product(
    civi_releases + [False], cms_variants + [False], php_releases + [False]
):
    tags.append({"civi": civi, "cms": cms, "php": php})


def parse_civi_release(civi):
    return major, minor, rev


# Attach tags to combinations
for tag in tags:

    # The civi tag has major, minor and rev aliases, e.g. 5-drupal and 5.10-drupal.
    # We generate combinations for these using itertools.product()
    tag_combos = []
    if tag["civi"]:
        civi_parts = tag["civi"].split(".")
        tag_combos.append(
            [tag["civi"], civi_parts[0] + "." + civi_parts[1], civi_parts[0]]
        )
    if tag["cms"]:
        tag_combos.append([tag["cms"]])
    if tag["php"]:
        tag_combos.append(["php" + tag["php"]])

    # Subsitiute
    key_parts = []
    for tag_key, tag_value in tag.items():
        if tag_value:
            key_parts.append(tag_value)
        else:
            key_parts.append(defaults[tag_key])
    key = "/".join(key_parts)
    for t in itertools.product(*tag_combos):
        t = "-".join(t)
        if t == "":
            t = "latest"
        combos[key]["tags"].append(t)

# Populate directories
root_dir = os.path.dirname(__file__)

templates = jinja2.Environment(loader=jinja2.FileSystemLoader("templates"))
for combo in combos.values():

    cms = combo["variables"]["cms"]
    combo_dir = root_dir + "/" + combo["dir"]

    run(["mkdir", "-p", combo_dir])

    # Dockerfile
    docker_file = templates.get_template("Dockerfile")
    docker_file.stream(**combo["variables"]).dump(f"{combo_dir}/Dockerfile")

    # civicrm.settings.php
    run(
        [
            "cp",
            f"templates/{cms}.civicrm.settings.php",
            f"{combo_dir}/civicrm.settings.php",
        ]
    )

    # cms.settings.php
    cms_settings_file_lookup = {
        "backdrop": "settings.php",
        "drupal": "settings.php",
        "wordpress": "wp-config.php",
    }
    cms_settings_file = cms_settings_file_lookup[cms]
    run(
        [
            "cp",
            f"templates/{cms}.{cms_settings_file}",
            f"{combo_dir}/{cms_settings_file}",
        ]
    )

    # init mysql, ready for load or install
    run(["cp", f"templates/{cms}.init", f"{combo_dir}/init"])

    # dump
    run(["cp", f"templates/{cms}.dump", f"{combo_dir}/dump"])

    # load
    run(["cp", "templates/load", combo_dir])

    # install (todo: split from init)
    run(["cp", f"templates/{cms}.install", f"{combo_dir}/install"])

    # common files
    run(
        [
            "cp",
            "templates/apache.conf",
            "templates/civicrm_dump.php",
            "templates/docker-civicrm-entrypoint",
            "templates/php.ini",
            combo_dir,
        ]
    )

    # CMS specific
    if cms == "wordpress":
        run(["cp", "templates/wordpress..htaccess", f"{combo_dir}/.htaccess"])


# Update tags section of the README.md
tag_text = []
for combo in combos.values():
    tag_list = " ".join([f"`{tag}`" for tag in combo["tags"]])
    combo_dir = combo["dir"]
    tag_text.append(f"* {tag_list} [({combo_dir})]({combo_dir})\n")
    # print("* " + combo["dir"] + str(combo["tags"]))

readme = list(open("README.md", "r"))
start = readme.index("<!---START_TAGS-->\n")
end = readme.index("<!---END_TAGS-->\n")
readme = readme[: start + 1] + tag_text + readme[end:]
print("".join(readme))

writeme = open("README.md", "w")
writeme.write("".join(readme))

exit()
#     print(start, end)


# Dump combos to a json file for other scripts
with open("combos.json", "w") as combos_file:
    json.dump(combos, combos_file, sort_keys=True, indent=4)
