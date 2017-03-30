<?php

/**
 * Created by PhpStorm.
 * User: Daniela
 * Date: 8/22/14
 * Time: 4:00 PM
 */
class Database
{
    private $link;

    /*
     * Connect to the db
     */
    public function __construct($host, $user, $pass, $db)
    {
        $this->link = mysqli_connect($host, $user, $pass, $db);

    }

    public function getLink()
    {
        return $this->link;
    }

    /*
     * Get all the elements from db that respect the rule you send
     */
    public function GetAll($query)
    {

        $qry = mysqli_query($this->link, $query);
        if (!$qry) {
            die($query);
        }
        $result = [];
        while ($row = mysqli_fetch_assoc($qry)) {
            $result[] = $row;
        }

        return $result;

    }

    /*
     * Count the number of elements from db that respect the rule in the query
     */
    public function GetOne($query)
    {
        $qry    = mysqli_query($this->link, $query);
        $result = mysqli_num_rows($qry);

        return $result;
    }

    /*
     * Count the number of elements from db that respect the rule in the query
     */
    public function GetCount($query)
    {
        $row = $this->GetRow($query);

        return reset($row);
    }

    /*
    * Get an element from db
    */
    public function GetRow($query)
    {
        $qry    = mysqli_query($this->link, $query);
        $result = mysqli_fetch_assoc($qry);

        return $result;

    }

    /*
     * Perform queries
     */
    public function Execute($sql)
    {
        if (!mysqli_query($this->link, $sql)) {
            return false;
        }

        return true;
    }

    /*
     * Escape variables for security
     */
    public function makeEscape($value)
    {
        return mysqli_real_escape_string($this->link, $value);
    }

    /*
     * Return the id of the last element used in the query
     */
    public function returnLastElementId()
    {
        $id = mysqli_insert_id($this->link);

        return $id;
    }
}
