SOURCES += $${PWD}/*.php
SOURCES += $${PWD}/*.py
SOURCES += $${PWD}/*.js
SOURCES += $${PWD}/*.html
SOURCES += $${PWD}/*.txt
SOURCES += $${PWD}/*.css
SOURCES += $${PWD}/*.json
SOURCES += $${PWD}/*.md

include ($${PWD}/ajax/ajax.pri)
include ($${PWD}/css/css.pri)
include ($${PWD}/docs/docs.pri)
include ($${PWD}/i18n/i18n.pri)
include ($${PWD}/modules/modules.pri)
include ($${PWD}/templates/templates.pri)
include ($${PWD}/php/php.pri)
