#!/bin/bash

# Some simple function to print a line (TODO Add some coloring and formatting later)
function printline {
echo $1;
}

function runScript {
docker exec -it sm-php "$@";
}

# Check if the .env file exists, if not try to copy the .env.example to .env

if ! [ -f ".env" ];
then
ENVIRONMENT_FILE=".env.example";
printline "==[ Copying $ENVIRONMENT_FILE to .env"
cp $ENVIRONMENT_FILE .env
local_uid=$(id -u);
local_gid=$(id -g);

echo "" >> ".env"
echo "DOCKER_UID=$local_uid" >> ".env"
echo "DOCKER_GID=$local_gid" >> ".env"
fi

echo "==[ DOCKER INIT ]==";
docker-compose up -d --remove-orphans

echo "==[ COMPOSER INSTALL ]==";
runScript composer install

# load the env file
export $(cat .env | grep APP | xargs)

# Generate an app key if it doesn't exist yes
if [ -z ${APP_KEY+x} ] || [ "$APP_KEY" == "" ]; then
printline "==[ Generating new app key ]=="
runScript php artisan key:generate
fi

printline "==[ All checks are passed, starting with the setup ]=="

force="n"
while getopts "f:" opt; do
case $opt in
f) force="$OPTARG"
;;
\?) echo "Invalid option -$OPTARG" >&2
;;
esac
done

if [ $force == "y" ]; then
runScript ./refresh.sh -f y
else
runScript ./refresh.sh
fi

docker exec --user=root -it sm-php chown -R www-data:www-data .

runScript ./build-frontend-dev.sh
