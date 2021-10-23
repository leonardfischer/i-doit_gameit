SHELL = /bin/sh
ROOT_DIR := $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
VERSION = $(shell cat VERSION)
ZIP_FILE="lfischer-gameit-${VERSION}.zip"

source:
	cd $(ROOT_DIR)
	if [ -f ${ZIP_FILE} ]; then rm ${ZIP_FILE}; fi
	mkdir -p /tmp/package/src/classes/modules/lfischer_gameit/
	cp -R * /tmp/package/src/classes/modules/lfischer_gameit/
	mv /tmp/package/src/classes/modules/lfischer_gameit/package.json /tmp/package/
	rm /tmp/package/src/classes/modules/lfischer_gameit/Makefile
	rm /tmp/package/src/classes/modules/lfischer_gameit/VERSION
	if [ -f /tmp/package/src/classes/modules/lfischer_gameit/*.zip ]; then rm /tmp/package/src/classes/modules/lfischer_gameit/*.zip; fi
	cd /tmp/package/ && zip -rqy ${ZIP_FILE} * && cp -f /tmp/package/${ZIP_FILE} $(ROOT_DIR)
	rm -R /tmp/package
