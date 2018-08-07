#include <iostream>
#include <array>
#include <vector>
#include <fstream>
#include <sstream>

#include "kdtree.h"

using namespace std;

const int chunk_size = 4;
const int bit_size = 64 * chunk_size;

// user-defined point type
// inherits std::array in order to use operator[]
class MyPoint : public std::array<double, bit_size>{
	public:
		// dimension of space (or "k" of k-d tree)
		// KDTree class accesses this member
		static const int DIM = bit_size;

		// the constructors
		MyPoint() {}
		MyPoint(string name, unsigned long long * nums){
			this->name = name;
			for(int i = 0; i < chunk_size; i++)
				for(int j = 0; j < 64; j++)
					(*this)[64 * i + j] = (nums[i] >> j) & j;
		}
		string getName(){
			return name;
		}
		int dist(MyPoint p){
			int dist = 0;
			for (size_t i = 0; i < DIM; i++)
				dist += ((*this)[i] != p[i]);
                        return dist;
		}
	private:
	string name;
};

void load_hash(string number, unsigned long long * vals){
	number = reverse(number.begin(), number.end()); //reverses string so we can work from right to left

	for(int i = 0; i < chunk_size; i++){
		vals[chunk_size - i - 1] = stoull(number.substr(0, 16), nullptr, 16);
		if(number.length() <= 16)
			break;
		number = number.substr(16);
	}
	if(number.length())
}

int main(int argc, char **argv){

	string hash_file = "hashes.csv", needle = "";

	for(int i = 1; i < argc; i++){
		if(argv[i][0] == '-' && argv[i][1] == 'f'){
			hash_file = string(argv[++i]);
		} else {
			needle = string(argv[i]);
		}
	}
	// generate points
	std::vector<MyPoint> haystack = {};

	// load points
	ifstream file(hash_file);
	while(file){
		string id, num;
		getline(file, id, ',');
		getline(file, num);
		if(id.size() == 0 || num.size() == 0) break;
		unsigned long long vals[chunk_size] = { 0 };
		load_hash(num, vals);
		haystack.push_back(MyPoint(id, vals));
	}

	// build k-d tree
	kdt::KDTree<MyPoint> kdtree(haystack);

	if(needle.length()){
		unsigned long long vals[chunk_size] = { 0 };
		load_hash(needle, vals);
		MyPoint query("", vals);
		// nearest neighbor
		const std::vector<int> knnIndices = kdtree.knnSearch(query, 1);
		cout << haystack[0].getName();
	} else {
		vector<MyPoint> newlyAdded = {}; //handles new points because we cannot add to kdtree currently
		string last = "";
		while(true){ //other program will handle writing eof when time comes
			string line;
			getline(cin, line);
			if(!line.length()) continue;
			stringstream ssline(line);

			string id;
			getline(ssline, id, ',');
			if(id.length() == 0 || id.compare(last) == 0) continue;
			if(!id.compare("-")) break; // if is -, quit
			getline(ssline, needle);
			if(needle.length() == 0) continue;

			MyPoint query(id, stoull(needle, nullptr,  16));
			// k-nearest neigbors search
			const std::vector<int> knnIndices = kdtree.knnSearch(query, 1);

			MyPoint best = haystack[knnIndices[0]];
			int bestDist = best.dist(query);
			for(auto it = newlyAdded.begin(); it != newlyAdded.end(); it++){
				int dist = it->dist(query);
				if(dist < bestDist){
					best = *it;
					bestDist = dist;
				}
				if(bestDist == 0) break;
			}
			newlyAdded.push_back(query);
			cout << id << "," << best.getName() << "\n";
			last = id;
		}
	}
	return 0;
}
