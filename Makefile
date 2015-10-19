NAME=wordsister
REPO=hendry/$(NAME)

.PHONY: start stop build sh

all: build

build:
	docker build -t $(REPO) .

start:
	docker run -d --name $(NAME) -v $(PWD)/www:/srv/http/ -v $(PWD)/logs:/var/log/nginx/ -p 82:80 $(REPO)

stop:
	docker stop $(NAME)
	docker rm $(NAME)

sh:
	docker exec -it $(NAME) /bin/sh
