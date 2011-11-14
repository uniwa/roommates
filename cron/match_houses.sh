#!/bin/sh

roommates_path="/home/devel/roommates/"
app_path="${roommates_path}app/"
cake_bin="${roommates_path}cake/console/cake"
cake_console="${roommates_path}cake/console"

cd "$app_path"
$cake_bin -app "$app_path" -console $cake_console "email"

exit 0
