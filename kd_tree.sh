#!/bin/bash

log="./tmp/log"

STOREF="./tmp/hashes_store.csv"
INF="./tmp/hashes_in.csv"
OUTF="./tmp/hashes_out.csv"

echo $(date) $0 "- Entered kd_tree.sh with params" $1 $2 >> $log

if [ $(pgrep kdtree | wc -l) -eq 0 ]; then
	echo $(date) $0 "- kdtree is not currently running, starting setup script" >> $log
	./kd_tree_setup.sh $STOREF $INF $OUTF
fi


if [ $(pgrep kd_tree_sleep.sh | wc -l) -ne 0 ]; then
	echo $(date) $0 "- killing previous kd_tree_sleep.sh script as we need kdtree now" >> $log
	pkill kd_tree_sleep.sh
fi

#takes in hash and returns closest

echo "$1,$2" >> $INF

out=''
while( [ -z $out ] ); do #test that program has not returned; probably need to make better in case prog closes
	out=$(grep $1 $OUTF)
	sleep 1
done

echo $out | cut -f 2 -d , $OUTF #gets similar id field of hashes_out

echo $(date) $0 "- Got result from kdtree " $($out | cut -f 2 -d , $OUTF) >> $log

echo "$1,$2" >> $STOREF

#

echo $(date) $0 "- Starting kd_tree_sleep.sh" >> $log
./kd_tree_sleep.sh $STOREF $INF $OUTF & disown
