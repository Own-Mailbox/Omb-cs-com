include /host/settings.sh
CFLAGS=  $(shell curl-config --libs)
all:
	sed -i 's/omb.one/$(MASTER_DOMAIN)/g' request.h
	gcc main.c request.c $(CFLAGS) -o client
install:
	cp client /usr/bin/omb-client
