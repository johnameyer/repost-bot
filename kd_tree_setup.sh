STOREF=$1
INF=$2
OUTF=$3

if( [ ! -e $INF ] ); do
        mkfifo $INF
done

kdtree -f $STOREF  < $INF > $OUTF

touch $OUTF
