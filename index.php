<?php
$db = new PDO("mysql:host=localhost;dbname=categories", "root", "");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function fetchCategoryTree($parent = 0, $spacing = '', $user_tree_array = '') {
    global $db;
    if (!is_array($user_tree_array))
    {
	$user_tree_array = array();
    }
    
    $stmt = $db->prepare("SELECT `cid`, `name`, `parent` FROM `category` WHERE 1 AND `parent` = $parent ORDER BY cid ASC");
    $stmt->execute();
    $parent = $stmt->fetchAll();
    $parentrows = $stmt->rowCount();
    if ($parentrows > 0) {
	foreach ($parent as $key => $row) {
	    $user_tree_array[] = array("id" => $row["cid"], "name" => $spacing . $row["name"]);
	    $user_tree_array = fetchCategoryTree($row["cid"],$spacing . '&nbsp;&nbsp;', $user_tree_array);
	}
    }
    return $user_tree_array;
}
?>
<select name="category_name">
<?php foreach (fetchCategoryTree() as $cl) { ?>
        <option value="<?php echo $cl["id"] ?>"><?php echo $cl["name"]; ?></option>
    <?php } ?>
</select>