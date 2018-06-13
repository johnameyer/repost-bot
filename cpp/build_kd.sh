#!/bin/sh

cp main.cpp kd_tree/main.cpp
rm -f kd_tree/sample.cpp
cd kd_tree/build/
cmake ../
make
cd ../..
cp kd_tree/build/kdtree kdtree
