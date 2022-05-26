<?php

DEFINE("DB_SERVER", "localhost");
DEFINE("DB_USER", "kopt6583_root");
DEFINE("DB_PASS", "Bismillah2022");
DEFINE("DB_NAME", "kopt6583_jakaid");

//define table name
DEFINE("TB_USER", "user");
DEFINE("TB_USERACCESS", "useraccess");
DEFINE("TB_MENU", "listmenu");

class mDatabase
{
    private $error = "";
    function conn()
    {
        return mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    }
    //function to check the input value
    function check_input($data)
    {
        if (is_string($data)) { //check if the data is a string
            $data = trim($data); //remove space in start and end of the value
            $data = stripslashes(implode("", explode("\\", $data))); //remove backslash
            $data = htmlspecialchars($data); //converts some predefined characters to HTML entities
            $data = str_replace(",", "&#44;", $data); //
            $data = str_replace("&amp;", "&", $data); //
            $data = mysqli_escape_string($this->conn(), $data); //
            return $data;
        }
        if (is_array($data)) { //check if the data is string array
            foreach ($data as $i => $value)
                $data[$i] = checkinput($value);
        }
        return $data;
    }

    //function to make array columns to string can be used in sql query
    function make_columns($data)
    {
        $data = "`" . str_replace("/", "`,`", implode("`,`", $data)) . "`";
        return $data;
    }

    //function to make array values to string can be used in sql query
    function make_values($data)
    {
        $data = "'" . implode("','", $data) . "'";
        $data = str_replace("''", "NULL", $data); //
        return $data;
    }

    /**
     * Get Data from the query 
     * @param sql Query
     */
    function get_data($query)
    {
        $conn = $this->conn();
        $result = mysqli_query($conn, $query);
        $sqldata = array();
        $this->error = mysqli_error($conn);
        while ($data = mysqli_fetch_array($result)) {
            $sqldata[] = $data;
        }
        //check the data more than 0
        if (count($sqldata) < 1) {
            return false;
        }

        return $sqldata;
    }

    /**
     * Get Data from the query 
     * @param sql Query
     */
    function get_query($query)
    {
        $result = mysqli_query($this->conn(), $query);
        return $result;
    }

    //Close the connection
    function close()
    {
        mysqli_close($this->conn());
    }

    //Query SELECT table in MySQL
    function select($table, $column, $option = null)
    {
        $sql = "SELECT $column 
                FROM $table $option";
        return $this->get_data($sql);
    }

    //Query SELECT with WHERE table in MySQL
    function select_where($table, $column, $where)
    {
        $sql = "SELECT $column 
                FROM $table
                WHERE $where";
        return $this->get_data($sql);
    }


    //Query SELECT JOIN 1 table with WHERE table in MySQL
    function select_join($table, $column, $on, $where, $join = "INNER")
    {
        $sql = "SELECT $column 
                FROM ";
        $table = explode(",", $table);
        $on = explode(",", $on);
        $sql .= $table[0];
        for ($x = 1; $x < count($table); $x++) {
            $i = $x - 1;
            $sql .= " " . $join . " JOIN " . $table[$x] . " ON " . $on[$i];
        }
        $sql .= " WHERE $where";
        return $this->get_data($sql);
    }

    //Function to check already column or not
    function check_column($table, $column)
    {
        $sql = $this->select_where("INFORMATION_SCHEMA.COLUMNS", "COLUMN_NAME", "TABLE_NAME='$table' AND COLUMN_NAME='$column'");
        if ($sql)
            return true;
        else
            return false;
    }

    //insert data to database
    function insert($table, $column, $values)
    {
        $sql = "INSERT INTO `$table` ($column)
                VALUES ($values)";
        $conn = $this->conn();
        if (mysqli_query($conn, $sql))
            $status = true;
        else {
            $status = false;
            $this->error = mysqli_error($conn);
        }

        return $status;
    }

    //delete data to database
    function delete($table, $where)
    {
        $sql = "DELETE FROM $table
                WHERE $where";
        $conn = $this->conn();
        if (mysqli_query($conn, $sql))
            $status = true;
        else {
            $status = false;
            $this->error = mysqli_error($conn);
        }
        return $status;
    }

    //update data to database
    function update($table, $columns, $values, $where)
    {
        $columns = explode(",", $columns);
        $values = explode(",", $values);
        $set = array();
        foreach ($columns as $index => $column) {
            $value = str_replace("&#44;", ",", $values[$index]); //replace html comma caracter to comma (,)
            $set[] = $column . "=" . $value;
        }
        $setcolumn = implode(",", $set);
        $sql = "UPDATE `$table` 
                SET $setcolumn
                WHERE $where";

        $conn = $this->conn();
        if (mysqli_query($conn, $sql))
            $status = true;
        else {
            $status = false;
            $this->error = mysqli_error($conn);
        }

        return $status;
    }
    function get_error()
    {
        return $this->error;
    }
}
