<?php


$conn = mysqli_connect("localhost", "root", "", "records");

if(!$conn){
    echo 'connection Failed!';
}