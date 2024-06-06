<?php
require 'db.php';

$db = new Database();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'view') {
        echo $db->displayMembers();
    }

    if ($_POST['action'] == 'getMembers') {
        $members = $db->getMembersForDropdown();
        $output = '<option value="">None</option>';
        foreach ($members as $member) {
            $output .= '<option value="' . $member['Id'] . '">' . $member['Name'] . '</option>';
        }
        echo $output;
    }

    if ($_POST['action'] == 'add') {
        $name = $_POST['name'];
        $parentId = $_POST['parent'] ? $_POST['parent'] : NULL;
        $db->addMember($name, $parentId);
        echo 'success';
    }
}
