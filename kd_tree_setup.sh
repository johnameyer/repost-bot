#!/bin/bash

log="log/bash.log"
errlog="log/err.log"

STOREF=$1
INF=$2
OUTF=$3

if [ ! -e $INF ]; then
	mkfifo $INF
	./fifo_hold.sh $INF & disown
fi

touch $OUTF

awk '{print NR "," $0}' $STOREF | sort -u -t , -k 3 | sort -t , -k 1 | cut -d , -f 2- > "$STOREF.tmp" && mv "$STOREF.tmp"  $STOREF

echo $(date) $0 "- Starting kdtree" >> $log 2>> $errlog
cpp/kdtree -f $STOREF  < $INF > $OUTF 2>> $errlog & disown
