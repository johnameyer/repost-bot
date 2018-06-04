STOREF=$1
INF=$2
OUTF=$3
MASTER="kd_tree.sh"

sleep 1

#check and make sure there are no other instances of kd_tree.sh running
if [ $(pgrep $MASTER | wc -l) -ge 2 ]; then
	exit
fi

#if not killed automatically, timeout means to cleanup
pkill kdtree

rm $INF $OUTF
