#!/bin/bash

docker build --rm -t budget_app_img ./

running_status="$(docker ps -a --filter "name=budget_app" --format "{{.Names}}")"

if ! [ -z "$running_status" ]
then
    echo "Found running budget_app container. Stopping."
    docker stop budget_app
    docker rm budget_app
fi

docker run -d -p 80:80 --name budget_app budget_app_img
