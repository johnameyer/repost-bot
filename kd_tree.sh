#!/bin/sh

STOREF="tmp/hashes_store.csv"
INF="tmp/hashes_in.csv"
OUTF="tmp/hashes_out.csv"

./kd_tree_setup.sh STOREF INF OUTF

flock -x $INF

pkill kd_tree_sleep.sh

#takes in hash and returns closest

echo "$1,$2" >> $INF

out=''
while( [ -z $out ] ); do #test that program has not returned; probably need to make better in case prog closes
	out=$(grep \"$1\" $OUTF)
	sleep 1
done

echo $out | cut -f 2 -d , $OUTF #gets similar id field of hashes_out

echo "$1,$2" >> $STOREF

#

./kd_tree_sleep.sh STOREF INF OUTF

flock -v $INF
