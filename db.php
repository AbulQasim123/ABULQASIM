<?php
class Database
{
    private $dsn = "mysql:host=127.0.0.1;port=3308;dbname=php_oop_test";
    private $user = "root";
    private $pass = "";
    private $conn;

    // Constructor
    public function __construct()
    {
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass);
            // echo "Connection Successful";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    // Function to fetch members
    public function fetchMembers($parentId = NULL)
    {
        try {
            $sql = "SELECT * FROM Members WHERE ParentId " . (is_null($parentId) ? "IS NULL" : "= :parentId");
            $stmt = $this->conn->prepare($sql);
            if (!is_null($parentId)) {
                $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            return [];
        }
    }

    // Function to display members
    public function displayMembers($parentId = NULL)
    {
        try {
            $members = $this->fetchMembers($parentId);
            $output = "";
            if (count($members) > 0) {
                $output .= "<ul>";
                foreach ($members as $member) {
                    $output .= "<li>" . htmlspecialchars($member['Name']);
                    $output .= $this->displayMembers($member['Id']);
                    $output .= "</li>";
                }
                $output .= "</ul>";
            }
            return $output;
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            return [];
        }
    }

    // Function to populate parent dropdown
    public function getMembersForDropdown()
    {
        try {
            $sql = "SELECT Id, Name FROM Members";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            return [];
        }
    }

    // Function to add member
    public function addMember($name, $parentId)
    {
        try {
            $sql = "INSERT INTO Members (Name, ParentId, CreatedDate) VALUES (:name, :parentId, :createdDate)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
            $createdDate = date('Y-m-d H:i:s');
            $stmt->bindParam(':createdDate', $createdDate, PDO::PARAM_STR);
            $stmt->execute();
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            return [];
        }
    }
}
