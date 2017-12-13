#!/bin/bash
#
# Start ${CONTAINER_NAME} docker container
# author: Loan Lassalle

TARGETED_ACTION="${1}"

IMAGE_TAG="centos/wechat"
CONTAINER_NAME="centos-wechat"

SRC_DATA="/Users/loanlassalle/Repositories/WeChat"
DEST_DATA="/var/www/html/wechat"

URL="http://localhost/wechat/controllers/init.php"
DEST_PORT="80"

# Start container
function start {
    local imageTag="${1}"
    local container_name="${2}"
    local src_directory="${3}"
    local dest_directory="${4}"
    local dest_port="${5}"

    echo "Starting ${container_name} docker container"
    docker build \
        --force-rm \
        --quiet \
        --tag "${imageTag}" .
    docker run \
        --detach \
        --name "${container_name}" \
        --volume /sys/fs/cgroup:/sys/fs/cgroup:ro \
        --volume "${src_directory}":"${dest_directory}" \
        --privileged \
        --publish "${dest_port}":80 \
        "${imageTag}"
}

# Stop container
function stop {
    local container_name="${1}"

    echo "Stopping ${container_name} docker container"
    docker stop "${container_name}" 2>/dev/null
    docker kill "${container_name}" 2>/dev/null
    docker rm \
        --volumes "${container_name}" 2>/dev/null
}

# Delete all data created by docker
function docker_clear {
    echo "Delete everything that was saved by docker (containers, volumes, images)"
    docker stop $(docker ps --all --quiet) 2>/dev/null && \
    docker kill $(docker ps --all --quiet) 2>/dev/null && \
    docker rm --force --volumes $(docker ps --all --quiet) 2>/dev/null && \
    docker volume rm --force $(docker volume ls --quiet) 2>/dev/null && \
    docker volume prune --force \
    docker volume rm --force $(docker volume ls --filter "dangling=true" --quiet) \
    docker image rm --force $(docker image ls --all --quiet) 2>/dev/null && \
    docker image prune --force 2>/dev/null && \
    docker image rm --force $(docker image ls --all --filter "dangling=true" --quiet) 2>/dev/null
}

# Display a little docker docs
function docker_docs {
    echo "Stop one or more running containers"
    echo "docker stop \${container_name}"
    echo "docker stop \$(docker ps --all --quiet)"
    echo
    echo "Kill one or more running containers"
    echo "docker kill \${container_name}"
    echo "docker kill \$(docker ps --all --quiet)"
    echo
    echo "Remove one or more containers"
    echo "docker rm --force --volumes \${container_name}"
    echo "docker rm --force --volumes \$(docker ps --all --quiet)"
    echo
    echo "Remove one or more volumes"
    echo "docker volume rm --force \${volume_name}"
    echo "docker volume rm --force \$(docker volume ls --quiet)"
    echo
    echo "Remove all unused volumes"
    echo "docker volume prune --force"
    echo "docker volume rm --force \$(docker volume ls --filter \"dangling=true\" --quiet)"
    echo
    echo "Remove one or more images"
    echo "docker image rm --force \${container_name}"
    echo "docker image rm --force \$(docker image ls --all --quiet)"
    echo
    echo "Remove all unused images"
    echo "docker image prune --force"
    echo "docker image rm --force \$(docker image ls --all --filter \"dangling=true\" --quiet)"
    echo
    echo "To read more details about docker CLI,"
    echo "follow this link https://docs.docker.com/engine/reference/commandline/cli/"
}

# Open OS default browser
function open_browser {
    local os_type="${1}"
    local url="${2}"
    local browser=""

    case "${os_type}" in
        darwin*)
            browser="open";;
        linux*)
            browser="xdg-open";;
        msys*)
            browser="start";;
        *)
            echo "unknown: ${os_type}" 
            exit 0;;
    esac

    echo "Open OS default browser"
    "${browser}" "${url}"
}

# Main
if [[ -z "${TARGETED_ACTION}" ]]
then
    start "${IMAGE_TAG}" "${CONTAINER_NAME}" "${SRC_DATA}" "${DEST_DATA}" "${DEST_PORT}" 
    open_browser "${OSTYPE}" "${URL}"
    read -p "Press ENTER key to stop ${CONTAINER_NAME} docker container ..."
    stop "${CONTAINER_NAME}"
elif [[ "${TARGETED_ACTION}" == "dd" ]]
then
    docker_docs
elif [[ "${TARGETED_ACTION}" == "dc" ]]
then
    docker_clear
else
    echo "Targeted action are [dd] for docker_docs, [dc] for docker_clear and nothing for default action."
fi