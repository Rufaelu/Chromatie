<?php

 class  databaseconnect
{
    private string $host='localhost';
    private string $user='root';
    private string $pass='';
    private string $db='chromatie';
    function getConnection()
    {
        $conn=mysqli_connect($this->host,$this->user,$this->pass,$this->db);
        if(!$conn)
        {
            die('connection error'.mysqli_connect_error($conn));
        }
        return $conn;
    }
}



