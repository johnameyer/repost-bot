#!/bin/sh

STOREF="./tmp/hashes_store.csv"
INF="./tmp/hashes_in.csv"
OUTF="./tmp/hashes_out.csv"

./kd_tree_setup.sh $STOREF $INF $OUTF

pkill kd_tree_sleep.sh

#takes in hash and returns closest

echo "$1,$2" >> $INF

echo "$1,$2" >> tmp/test.txt

out=''
while( [ -z $out ] ); do #test that program has not returned; probably need to make better in case prog closes
	out=$(grep \"$1\" $OUTF)
	echo $out >> tmp/test.txt
	sleep 1
done

echo $out | cut -f 2 -d , $OUTF #gets similar id field of hashes_out

echo $out | cut -f 2 -d , $OUTF >> tmp/test.txt

echo "$1,$2" >> $STOREF

#

./kd_tree_sleep.sh $STOREF $INF $OUTF &
