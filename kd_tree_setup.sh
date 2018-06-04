STOREF=$1
INF=$2
OUTF=$3

if [ ! -e $INF ]; then
	mkfifo $INF
fi

./kdtree -f $STOREF  < $INF > $OUTF &

touch $OUTF
