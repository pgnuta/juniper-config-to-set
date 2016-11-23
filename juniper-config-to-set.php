#!/usr/bin/php
<?php

/*****************************************************************************************
*
* Written by Tim Price 24/11/2016
*
* The purpose of this script is to take a juniper config and convert it to set commands.
* There was a business need to audit thbe correctness of Juniper configs and the process 
* of logging into each Juniper device and dumping the configs in set format was too 
* burdensome on both the Juniper devices in question but also extended the execution time 
* of the audit scripts by some minutes.
*
* The script will take a juniper config piped to it from STDIN and output the results to
* STDOUT
* 
* Usage example:  `cat juniper-config.txt | juniper-config-to-set.php`
*
*****************************************************************************************/

function endswith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

function addtotree($tree,$word) {
	array_push($tree,$word);
	return $tree;
}

function removewordfromtree($tree) {
	array_pop($tree);
	return $tree;
}

function printtree($tree) {
	return implode(" ",$tree);
}

$tree=array();

while($line = fgets(STDIN)){ 

	// Skip any commented lines
	if(strpos($line,'#')===0) {
		continue;
	}

	// Trim white space from the line
	$line=trim($line);

	// What remains is a series of tests to see what the line ends with.

	// This test matches a ';' character which means its a leaf and we can output the 
	// whole line to STDOUT
	if(endswith($line,';')) {

		// Trim the semi-colon from the end of the line, we don't need that.
		$line = rtrim($line,';');
		
		// Test to see if the tree is empty, if it is then we'll just print the leaf and 
		// not the tree to avoid excess white spaces		
		if(count($tree)==0) {
			echo "set $line\n";
		} else {
			echo "set " . printtree($tree) . " $line\n";
		}
	}
	
	// This test matches a '{' character on the end of the line and means we need to add a
	// branch to the tree
	if(endswith($line,'{')) {
		$line = rtrim($line,' {');
		$tree=addtotree($tree,$line);
	}

	// This test matches a '}' character on the end of the line and means we need to 
	// remove the last branch that we added to the tree
	if(endswith($line,'}')) {
		$tree=removewordfromtree($tree);
	}
}

?>
