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

echo $(date) $0 "- Starting kdtree" >> $log 2>>$errlog
cpp/kdtree -f $STOREF  < $INF > $OUTF 2>> $errlog & disown

touch $OUTF
