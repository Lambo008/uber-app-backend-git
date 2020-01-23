<?php
$con = mysqli_connect('localhost','root','dazecorpdatabase420','SuraTest');
if(!$con)
{
	echo "Connection UnSuccessful";
}

// $finalResponse = array();
// print_r(buildChildren($con,'0'));

// echo json_encode($finalResponse);

$arraytable = array();
	$query = "select * from school_level_exam";
	$list_of_categories = mysqli_query($con, $query);
    while($fetch_categories = mysqli_fetch_assoc($list_of_categories))
    {
	    
		$categories[] = $fetch_categories;
    }

echo json_encode($categories);


function buildChildren($con,$parent)
{
	getChildren($con,$parent);
}
function getChildren($con,$parent)
{
	$query = "select * from school_level_exam where parents = $parent";
	$list_of_categories = mysqli_query($con, $query);
    while($fetch_categories = mysqli_fetch_assoc($list_of_categories))
    {
		$categories[] = $fetch_categories;
// 		 print_r($fetch_categories);
		buildChildren($con,$fetch_categories['id']);	
    }
    return $categories;//array(''=>$categories,''=>$cat);
}

function parseTree($tree, $root = 0) {
    $return = array();
    # Traverse the tree and search for direct children of the root
    foreach($tree as $child => $parent) {
        # A direct child is found
        if($parent == $root) {
            # Remove item from tree (we don't need to traverse this again)
            unset($tree[$child]);
            # Append the child into result array and parse its children
            $return[] = array(
                'name' => $child,
                'children' => parseTree($tree, $child)
            );
        }
    }
    return empty($return) ? null : $return;    
}

?>