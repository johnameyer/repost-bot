#!/bin/bash

log="log/bash.log"

STOREF=$1
INF=$2
OUTF=$3
MASTER="kd_tree.sh"


echo $(date) $0 "- Waiting" >> $log
sleep 6

#check and make sure there are no other instances of kd_tree.sh running
if [ $(pgrep -f $MASTER | wc -l) -ge 1 -o $(pgrep -f $0 | wc -l) -ge 2 ]; then
	echo $(date) $0 "- Not the last sleep script" >> $log
	exit
fi

#if not killed automatically, timeout means to cleanup
echo $(date) $0 "- killing kdtree" >> $log
echo "-" > $INF
pkill kdtree

pkill -f fifo_hold.sh

rm $INF $OUTF
