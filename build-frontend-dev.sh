#!/bin/bash

yes_im_sure="n"

while getopts ":f:" opt; do
  case $opt in
    f) yes_im_sure="$OPTARG"
    ;;
    \?) echo "Invalid option -$OPTARG" >&2
    ;;
  esac
done

echo "==[ Generate Ziggy Routes ]=="
php artisan ziggy:generate

echo "==[ Install NPM dependencies ]=="
yarn

echo "==[ Build assets ]=="
yarn dev


