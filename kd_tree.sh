#!/bin/sh

#takes in hash and returns closest

echo $1 >> hashes_in.csv

out=''
while( [ -z $out ] ); do
	out=$(grep \"$1\" hashes_out.csv)
done

cut -f 2 -d , hashes_out.csv

#clean out files when all processes finish and program stopped normally
