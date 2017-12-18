# makefile for testing
#
# its optimized for use in a NFS
# environment that uses root_squash

NAME=nc
.PHONY: all build build-nocache run 

all: build

build:
	mkdir -p /tmp/$(NAME)
	rsync -av $(PWD)/* /tmp/$(NAME) && \
	sudo chown -R root:root /tmp/$(NAME) &&\
	sudo docker build -t $(NAME) -f /tmp/$(NAME)/Dockerfile /tmp/$(NAME)
	sudo rm -rf /tmp/$(NAME)	

run:
	sudo docker run --name=$(NAME) -ti --rm $(NAME) bash

clean:
	sudo rm -rf /tmp/$(NAME)
	sudo docker rm -f $(NAME) || true
	sudo docker rmi $(NAME) || true
