#!/bin/sh

app_path="/home/devel/roommates/"
cake_bin="${app_path}cake/console/cake"
cake_script= "email"

cd "$app_path"
$cake_bin $cake_script $@

exit 0
