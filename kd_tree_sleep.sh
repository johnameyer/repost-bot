STOREF=$1
INF=$2
OUTF=$3
MASTER="kd_tree.sh"

sleep 10

#check and make sure there are no other instances of kd_tree.sh running
if( [ 1 -ge pgrep $MASTER ] ); do
	exit
fi

#if not killed automatically, timeout means to cleanup
pkill kdtree

rm $INF $OUTF
