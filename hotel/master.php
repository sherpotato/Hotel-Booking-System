<head>
<title>CPSC 304 Project</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="main.css">
<style>
table, td, th {
border: 1px solid #ddd;
padding: 8px;
}
tr:nth-child(even){background-color: #f2f2f2;}
    
tr:hover {background-color: #ddd;}
    
th {
padding-top: 12px;
padding-bottom: 12px;
text-align: left;
background-color: black;
color: white;
}
</style>
</head>
    
<body>
<header>
<nav>
<h1>MASTER</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="customer.php">Customer</a></li>
<li><a href="staff.php">staff</a></li>
<li>Master</li>
</ul>
</nav>
</header>

<p>Create a branch:</p>
<form method="POST" action="master.php">
<pre>
Branch ID       <input type="text" name="create_bid" size="30">
address         <input type="text" name="create_address" size="30">
city            <input type="text" name="create_city" size="30">
</pre>
<input type="submit" value="create" name="createBranch">
</form>

<p>Delete a branch:</p>
<form method="POST" action="master.php">
<pre>
Branch ID       <input type="text" name="delete_bid" size="30">
</pre>
<input type="submit" value="delete" name="deleteBranch">
</form>


<p>Create a Room:</p>
<form method="POST" action="master.php">
<pre>
roomNo          <input type="text" name="room_no" size="30">
occupationStatus<input type="text" name="room_occupationStatus" size="30">
roomTypeName    <input type="text" name="room_roomTypeName" size="30">
Branch ID       <input type="text" name="room_bid" size="30">
</pre>
<input type="submit" value="submit" name="createRoom">
</form>

<p>Delete a Room:</p>
<form method="POST" action="master.php">
<pre>
RoomNo          <input type="text" name="delete_room" size="30">
Branch ID       <input type="text" name="delete_bid" size="30">
</pre>
<input type="submit" value="delete" name="deleteRoom">
</form>

<p>Create a typerate:</p>
<form method="POST" action="master.php">
<pre>
Capacity        <input type="text" name="create_capacity" size="30">
Feature         <input type="text" name="create_feature" size="30">
Price           <input type="text" name="create_price" size="30">
</pre>
<input type="submit" value="create" name="createtypeRate">
</form>

<p>Delete a typerate:</p>
<form method="POST" action="master.php">
<pre>
Capacity        <input type="text" name="delete_capacity" size="30">
</pre>
<input type="submit" value="delete" name="deletetypeRate">
</form>

<p>Update a typerate:</p>
<form method="POST" action="master.php">
<pre>
Capacity        <input type="text" name="update_capacity" size="30">
New price       <input type="text" name="update_rate" size="30">
</pre>
<input type="submit" value="update" name="updatetypeRate">
</form>

<p>Create a typeInfo:</p>
<form method="POST" action="master.php">
<pre>
RoomType Name   <input type="text" name="create_roomTypeName" size="30">
Capacity        <input type="text" name="create_capacity" size="30">
Feature         <input type="text" name="create_feature" size="30">
Availability    <input type="text" name="create_availability" size="30">
</pre>
<input type="submit" value="create" name="createtypeInfo">
</form>

<p>Delete a typeInfo:</p>
<form method="POST" action="master.php">
<pre>
RoomType Name   <input type="text" name="delete_roomTypeName" size="30">
</pre>
<input type="submit" value="delete" name="deletetypeInfo">
</form>

<p>Create a facility:</p>
<form method="POST" action="master.php">
<pre>
Facility Name   <input type="text" name="create_facilityName" size="20">
FromTime        <input type="text" name="create_fromTime" size="20">
ToTime          <input type="text" name="create_toTime" size="20">
Branch ID       <input type="text" name="create_bid" size="20">
</pre>
<input type="submit" value="create" name="createFacility">
</form>

<p>Delete a facility:</p>
<form method="POST" action="master.php">
<pre>
Facility Name   <input type="text" name="delete_facilityName" size="30">
Branch ID       <input type="text" name="delete_bid" size="30">
</pre>
<input type="submit" value="delete" name="deleteFacility">
</form>

<p>Review Keyword Search:</p>
<form method="POST" action="master.php">
<pre>
Review Number   <input type="text" name="review_keyword" size="30">
</pre>
<input type="submit" value="search" name="keyword_search">
</form>

<p>Delete a review:</p>
<form method="POST" action="master.php">
<pre>
Review Number   <input type="text" name="delete_review" size="30">
</pre>
<input type="submit" value="delete" name="deleteReview">
</form>

<?php

$success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = OCILogon("ora_m0c1b", "a46859161", "dbhost.ugrad.cs.ubc.ca:1522/ug");
    
    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work
        
        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the
            // connection handle
            echo htmlentities($e['message']);
            $success = False;
        }
        
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        } else {
            
        }
        return $statement;
        
    }
    
	function executeBoundSQL($cmdstr, $list) {
        /* Sometimes the same statement will be executed for several times ... only
         the value of variables need to be changed.
         In this case, you don't need to create the statement several times;
         using bind variables can make the statement be shared and just parsed once.
         This is also very useful in protecting against SQL injection.
         See the sample code below for how this functions is used */
        
        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr);
        
        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn);
            echo htmlentities($e['message']);
            $success = False;
        }
        
        foreach ($list as $tuple) {
            foreach ($tuple as $bind => $val) {
                //echo $val;
                //echo "<br>".$bind."<br>";
                OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
                
            }
            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($statement); // For OCIExecute errors pass the statement handle
                echo htmlentities($e['message']);
                echo "<br>";
                $success = False;
            }
        }
        
    }
    
       function printResult_HotelBranch($result) { //prints results from a select statement
        echo "<br>Got data from table HotelBranch:<br>";
        echo "<table>";
        echo "<tr><th>Branch ID</th><th>Address</th><th>City</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
       function printResult_typeInfo($result) { //prints results from a select statement
        echo "<br>Got data from table typeInfo:<br>";
        echo "<table>";
        echo "<tr><th>roomTypeName</th><th>capacity</th><th>feature</th><th>availability</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            <td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    function printResult_typeRate($result) { //prints results from a select statement
        echo "<br>Got data from table typeRate:<br>";
        echo "<table>";
        echo "<tr><th>capacity</th><th>feature</th><th>price</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }

        function printResult_Facility($result) { //prints results from a select statement
        echo "<br>Got data from table Facility:<br>";
        echo "<table>";
        echo "<tr><th>facilityName</th><th>fromTime</th><th>toTime</th><th>bid</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            <td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
        function printResult_Room($result) { //prints results from a select statement
        echo "<br>Got data from table Room:<br>";
        echo "<table>";
        echo "<tr><th>roomNo</th><th>availability</th><th>roomTypeName</th><th>bid</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            <td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    function print_allReview($result) { //prints results from a select statement
        echo "<br>Reviews:<br>";
        echo "<table>";
        echo "<tr><th>Review Number</th><th>Room Type</th><th>Rating</th><th>Content</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    
    // Connect Oracle...
    if ($db_conn) {
        if (array_key_exists('keyword_search', $_POST)) {
            $aaa = $_POST['review_keyword'];
            
            $xxx = executePlainSQL("SELECT * FROM Review WHERE content LIKE '%".$aaa."%'");
            print_allReview($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('deleteReview', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['delete_review']);
            $alltuples = array ($tuple);
            executeBoundSQL("DELETE FROM Review WHERE reviewNo = :bind1", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM Review");
            print_allReview($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('createRoom', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['room_no'],
                    ":bind2" => $_POST['room_occupationStatus'],
                    ":bind3" => $_POST['room_roomTypeName'],
                    ":bind4" => $_POST['room_bid']);
            $alltuples = array ($tuple);
            executeBoundSQL("INSERT INTO Room VALUES(:bind1, :bind2, :bind3, :bind4)", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM Room");
            printResult_Room($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('deleteRoom', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['delete_room'],
                    ":bind2" => $_POST['delete_bid']);
            $alltuples = array ($tuple);
            executeBoundSQL("DELETE FROM Room WHERE roomNo = :bind1 and bid = :bind2", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM Room");
            printResult_Room($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('createFacility', $_POST)) {
            $tuple = array (
                            ":bind1" => $_POST['create_facilityName'],
                            ":bind2" => $_POST['create_fromTime'],
                            ":bind3" => $_POST['create_toTime'],
                            ":bind4" => $_POST['create_bid']);
            $alltuples = array ($tuple);
            executeBoundSQL("INSERT INTO Facility VALUES(:bind1, :bind2, :bind3, :bind4)", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM Facility");
            // printResult_Facility($xxx);
            OCICommit($db_conn);
        } else
        
        if (array_key_exists('deleteFacility', $_POST)) {
            $tuple = array (
                            ":bind1" => $_POST['delete_facilityName'],
                            ":bind2" => $_POST['delete_bid']);
            $alltuples = array ($tuple);
            executeBoundSQL("DELETE FROM Facility WHERE facilityName = :bind1 and bid = :bind2", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM Facility");
            // printResult_Facility($xxx);
            OCICommit($db_conn);
        } else
		
  			
        if (array_key_exists('createtypeInfo', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['create_roomTypeName'],
                    ":bind2" => $_POST['create_capacity'],
                    ":bind3" => $_POST['create_feature'],
                    ":bind4" => $_POST['create_availability']);
            $alltuples = array ($tuple);
            executeBoundSQL("INSERT INTO typeInfo VALUES(:bind1, :bind2, :bind3, :bind4)", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM typeInfo");
            printResult_typeInfo($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('deletetypeInfo', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['delete_roomTypeName']);
            $alltuples = array ($tuple);
            executeBoundSQL("DELETE FROM typeInfo WHERE roomTypeName = :bind1", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM typeInfo");
            printResult_typeInfo($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('createtypeRate', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['create_capacity'],
                    ":bind2" => $_POST['create_feature'],
                    ":bind3" => $_POST['create_price']);
            $alltuples = array ($tuple);
            executeBoundSQL("INSERT INTO typeRate VALUES(:bind1, :bind2, :bind3)", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM typerate");
            printResult_typeRate($xxx);
            OCICommit($db_conn);
        } else

        if (array_key_exists('deletetypeRate', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['delete_capacity']);
            $alltuples = array ($tuple);
            $result = executeBoundSQL("DELETE FROM typeRate WHERE capacity = :bind1", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM typerate");
            printResult_typeRate($xxx);
            OCICommit($db_conn);
        } else
        
        if (array_key_exists('updatetypeRate', $_POST)) {
            $tuple = array (
                    ":bind1" => $_POST['update_capacity'],
                    ":bind2" => $_POST['update_rate']);
            $alltuples = array ($tuple);
            $result = executeBoundSQL("update typeRate set price = :bind2 WHERE capacity = :bind1", $alltuples);
            $xxx = executePlainSQL("SELECT * FROM typerate");
            printResult_typeRate($xxx);
            OCICommit($db_conn);
        } else
     
     if (array_key_exists('createBranch', $_POST)) {
                $tuple = array (
                                ":bind1" => $_POST['create_bid'],
                                ":bind2" => $_POST['create_address'],
                                ":bind3" => $_POST['create_city']);

    $alltuples = array ($tuple);
    executeBoundSQL("INSERT INTO HotelBranch VALUES(:bind1, :bind2, :bind3)", $alltuples);
    $xxx = executePlainSQL("SELECT * FROM HotelBranch");
    // printResult_HotelBranch($xxx);

    OCICommit($db_conn);
    } else
        
    if (array_key_exists('deleteBranch', $_POST)) {
                $tuple = array (
                                ":bind1" => $_POST['delete_bid']);
    $alltuples = array ($tuple);
    executeBoundSQL("DELETE FROM HotelBranch WHERE bid = :bind1", $alltuples);
    $xxx = executePlainSQL("SELECT * FROM HotelBranch");
    // printResult_HotelBranch($xxx);
    OCICommit($db_conn);
    }
    
    if($_POST && !$success) {echo "Failed";}
        //Commit to save changes...
        OCILogoff($db_conn);
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
