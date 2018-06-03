#include <iostream>
#include <array>
#include <vector>
#include <fstream>

#include "kdtree.h"

using namespace std;

// user-defined point type
// inherits std::array in order to use operator[]
class MyPoint : public std::array<double, 64>{
	public:
		// dimension of space (or "k" of k-d tree)
		// KDTree class accesses this member
		static const int DIM = 64;

		// the constructors
		MyPoint() {}
		MyPoint(string name, unsigned long long num){ 
			this->name = name;
			for(int i = 0; i < 64; i++)
				(*this)[i] = (num >> i) & 1;
		}
		string getName(){
			return name;
		}

	private:
	string name;
};

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
		unsigned long long val = stoull(num, nullptr, 16); 
		haystack.push_back(MyPoint(id, val));
	}

	// build k-d tree
	kdt::KDTree<MyPoint> kdtree(haystack);

	if(needle.length()){
		MyPoint query("", stoull(needle, nullptr,  16));
		// nearest neighbor
		const std::vector<int> knnIndices = kdtree.knnSearch(query, 1);
		cout << haystack[0].getName();
	} else {
		while(true){ //other program will handle writing eof when time comes
			string id;
			getline(cin, id, ',');
			if(id.length() == 0) continue;
			getline(cin, needle);
			//cout << needle.length();
			if(needle.length() == 0) continue;
			if(!id.compare("-")) break; // if is -, quit
			cout << "- " << needle << "," << id << "\n";
			MyPoint query(id, stoull(needle, nullptr,  16));
			// k-nearest neigbors search
			const std::vector<int> knnIndices = kdtree.knnSearch(query, 1);
			cout << id << "," << haystack[knnIndices[0]].getName() << "\n";
		}
	}
	return 0;
}
