<?php
$db_server="localhost";
$db_user="root";
$db_password="";
$db_name="EasyFind";

$conn=mysqli_connect($db_server,$db_user,$db_password,$db_name);
if(!$conn)
{
    echo "Error: Connection Failed".mysqli_connect_error();
    exit();
}
$sql="CREATE TABLE IF NOT EXISTS Students(
RegistrationID VARCHAR(50) PRIMARY KEY,
Student_Name VARCHAR(100),
email VARCHAR(50),
Password VARCHAR(255),
Department VARCHAR(50),
year_of_study VARCHAR(20),
section varchar(10)
)";
$result=mysqli_query($conn,$sql);
if(!$result)
{
    echo "Error: Could not create Table".mysqli_error($conn);
}

$lostform="CREATE TABLE IF NOT EXISTS LostItems(
ItemID INT AUTO_INCREMENT PRIMARY KEY,
RegistrationId VARCHAR(50),
ItemName VARCHAR(20),
Category VARCHAR(20),
Location_Lost VARCHAR(255),
Date_Lost datetime,
Contact VARCHAR(15),
Description VARCHAR(255),
file varchar(255),
FOREIGN KEY (RegistrationID) References Students(RegistrationID)
)";
if(!mysqli_query($conn,$lostform))
{
    echo "Error: Could not create Table".mysqli_error($conn);
}

$foundform="CREATE TABLE IF NOT EXISTS FoundItems(
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    RegistrationId VARCHAR(50),
    ItemName VARCHAR(20),
    Category VARCHAR(20),
    Location_Found VARCHAR(255),
    Date_Found datetime,
    Contact VARCHAR(15),
    Description VARCHAR(255),
    file varchar(255),
    FOREIGN KEY (RegistrationID) References Students(RegistrationID)
    )";
    if(!mysqli_query($conn,$foundform))
    {
        echo "Error: Could not create Table".mysqli_error($conn);
    }

$rewards="CREATE TABLE IF NOT EXISTS Rewards(
RegistrationId VARCHAR(15) PRIMARY KEY,
Name VARCHAR(50),
Points INT DEFAULT 0,
Badges VARCHAR(50))";

if(!mysqli_query($conn,$rewards))
{
    echo "Error: Could not create table ".mysqli_error($conn);
}


// Create claims table
$claims_table = "CREATE TABLE IF NOT EXISTS claims (
    claim_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    claimer_id VARCHAR(255),
    finder_id VARCHAR(255),
    claim_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    description TEXT,
    contact VARCHAR(255),
    FOREIGN KEY (item_id) REFERENCES founditems(ItemID)
)";

if(!mysqli_query($conn, $claims_table))
{
    echo "Error: Could not create table ".mysqli_error($conn);
}


// Create notifications table
$notifications_table = "CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255),
    title VARCHAR(255),
    message TEXT,
    type ENUM('claim', 'accept', 'reject') NOT NULL,
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    related_claim_id INT,
    FOREIGN KEY (related_claim_id) REFERENCES claims(claim_id)
)";

if(!mysqli_query($conn, $notifications_table))
{
    echo "Error: Could not create table ".mysqli_error($conn);
}

$feedback="CREATE TABLE IF NOT EXISTS Feedback(
Feedback_ID INT  AUTO_INCREMENT PRIMARY KEY,
RegistrationId VARCHAR(20),
Feedback_Message TEXT,
CreatedAt  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(RegistrationId) References Students(RegistrationID)
  )";
if(!mysqli_query($conn,$feedback))
{
    echo "Error: Could not create table ".mysqli_error($conn);
}

?>