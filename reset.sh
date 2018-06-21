pkill -f kd
pkill -f fifo_hold
pkill -f "tail -f /dev/null"

rm tmp/hashes_in.csv tmp/hashes_out.csv
rm log/*
