#!/bin/bash

log="tmp/log"

STOREF=$1
INF=$2
OUTF=$3

if [ ! -e $INF ]; then
	mkfifo $INF
fi

echo $(date) $0 "- Starting kdtree" >> $log
./kdtree -f $STOREF  < $INF > $OUTF & disown

touch $OUTF
