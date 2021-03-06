#!/bin/bash

#============================================
# Requirements:
# 
# Configuration in etc/piLogger.conf
#
# #------------------------
# # shiftr.io
# #------------------------
# shiftrAuth="Authentication String"
# 
#============================================

this_dir=$(cd `dirname $0`; pwd)

. $PILOGGER_BASE_DIR/etc/piLogger.conf
. $PILOGGER_BASE_DIR/bin/functions

logIt "  - Sending data to shiftr.io: $HOSTNAME:$PILOGGER_SENSOR:$PILOGGER_DATA"
curl -X POST "http://$shiftrAuth@broker.shiftr.io/$HOSTNAME/${PILOGGER_SENSOR}/${PILOGGER_METRIC_TYPE}" -d "metricValue:${PILOGGER_DATA}"
echo ""
