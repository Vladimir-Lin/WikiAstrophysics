SOURCES += $${PWD}/*.php
SOURCES += $${PWD}/*.py
SOURCES += $${PWD}/*.js
SOURCES += $${PWD}/*.html
SOURCES += $${PWD}/*.txt
SOURCES += $${PWD}/*.css
SOURCES += $${PWD}/*.json

include ($${PWD}/Common/Common.pri)
include ($${PWD}/Earth/Earth.pri)
include ($${PWD}/Moon/Moon.pri)
include ($${PWD}/Sun/Sun.pri)
