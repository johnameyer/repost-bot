#!/bin/bash

log="tmp/log"

STOREF=$1
INF=$2
OUTF=$3
MASTER="kd_tree.sh"


echo $(date) $0 "- Waiting" >> $log
sleep 10

#check and make sure there are no other instances of kd_tree.sh running
if [ $(pgrep $MASTER | wc -l) -ge 2 ]; then
	echo $(date) $0 "- Not the last sleep script" >> $log
	exit
fi

#if not killed automatically, timeout means to cleanup
echo $(date) $0 "- killing kdtree" >> $log
echo "-" > $INF
pkill kdtree

rm $INF $OUTF
