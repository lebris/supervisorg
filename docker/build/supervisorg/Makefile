NAME=supervisorg
TAG?=latest

all:
	docker build --force-rm -t $(NAME):$(TAG) .

nocache:
	docker build --force-rm --no-cache -t $(NAME):$(TAG) .
