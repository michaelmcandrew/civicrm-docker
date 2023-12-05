#!/usr/bin/env python3

import os
from subprocess import run
import jinja2
import itertools
import json
from variables import cms_variants, php_releases, defaults, civi_releases

# Generate combinations
combos = {}
for civi, cms, php in itertools.product(civi_releases, cms_variants, php_releases):
    major = civi.split(".")[0]
    key = f"{civi}/{cms}/{php}"
    combos[key] = {
        "tags": [],
        "variables": {"civi": civi, "cms": cms, "php": php},
        "dir": f"{major}/{cms}/php{php}",
    }


# Generate tags
tags = []
for civi, cms, php in itertools.product(
    civi_releases + [False], cms_variants + [False], php_releases + [False]
):
    tags.append({"civi": civi, "cms": cms, "php": php})

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

root_dir = os.path.dirname(os.path.abspath(__file__))
run(["rm", "-r", root_dir + "/5"])
templates = jinja2.Environment(loader=jinja2.FileSystemLoader(root_dir + "/templates"))
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
            f"{root_dir}/templates/{cms}.civicrm.settings.php",
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
            f"{root_dir}/templates/{cms}.{cms_settings_file}",
            f"{combo_dir}/{cms_settings_file}",
        ]
    )

    # init mysql, ready for load or install
    run(
        [
            "cp",
            f"{root_dir}/templates/{cms}.civicrm-docker-init",
            f"{combo_dir}/civicrm-docker-init",
        ]
    )

    # dump
    run(
        [
            "cp",
            f"{root_dir}/templates/{cms}.civicrm-docker-dump",
            f"{combo_dir}/civicrm-docker-dump",
        ]
    )

    # load
    run(["cp", f"{root_dir}/templates/civicrm-docker-load", combo_dir])

    # install (todo: split from init)
    run(
        [
            "cp",
            f"{root_dir}/templates/{cms}.civicrm-docker-install",
            f"{combo_dir}/civicrm-docker-install",
        ]
    )

    # common files
    run(
        [
            "cp",
            f"{root_dir}/templates/.my.cnf",
            f"{root_dir}/templates/apache.conf",
            f"{root_dir}/templates/apache-sites-available-default.conf",
            f"{root_dir}/templates/civicrm_dump.php",
            f"{root_dir}/templates/civicrm-docker-entrypoint",
            f"{root_dir}/templates/msmtp-wrapper",
            combo_dir,
        ]
    )

    # CMS specific
    if cms == "wordpress":
        run(
            [
                "cp",
                f"{root_dir}/templates/wordpress..htaccess",
                f"{combo_dir}/.htaccess",
            ]
        )
        run(
            [
                "cp",
                f"{root_dir}/templates/wordpress.wordpress-update-domain",
                f"{combo_dir}/wordpress-update-domain",
            ]
        )


# Update tags section of the README.md
tag_text = ["\n"]
for combo in combos.values():
    tag_list = " ".join([f"`{tag}`" for tag in combo["tags"]])
    combo_dir = combo["dir"]
    tag_text.append(f"- {tag_list} [({combo_dir})]({combo_dir})\n")
tag_text.append("\n")
print(root_dir + "/README.md")

readme = list(open(root_dir + "/README.md", "r"))
start = readme.index("<!---START_TAGS-->\n")
end = readme.index("<!---END_TAGS-->\n")
readme = readme[: start + 1] + tag_text + readme[end:]
writeme = open(root_dir + "/README.md", "w")
writeme.write("".join(readme))

# Dump combos to a json file for other scripts
with open(root_dir + "/combos.json", "w") as combos_file:
    json.dump(combos, combos_file, sort_keys=True, indent=4)
