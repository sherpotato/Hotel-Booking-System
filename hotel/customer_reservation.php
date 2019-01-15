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
<h1>RESERVATION</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="customer.php">Customer</a></li>
<li>Reservation</li>
<li><a href="customer_review.php">Review</a></li>
<li><a href="customer_profile.php">Profile</a></li>
</ul>
</nav>
</header>

<p>View your Reservations and Orders with ID:</p>
<form method="POST" action="customer_reservation.php">
<pre>
ID   <input type="text" name="id_number" size="20">
</pre>
<input type="submit" value="view" name="view">


<p>Create a reservation:</p>
<form method="POST" action="customer_reservation.php">
<pre>
Confrim Number <input type="text" name="create_confNo" size="20">
Branch ID      <input type="text" name="create_bid" size="20">
Room type      <input type="text" name="create_roomTypeName" size="20">
ID             <input type="text" name="create_idNo" size="20">
From Date      <input type="text" name="create_fromDate" size="20">
To Date        <input type="text" name="create_toDate" size="20">
</pre>
<input type="submit" value="create" name="createReservation">
</form>

<p>Cancle a reservation:</p>
<form method="POST" action="customer_reservation.php">
<pre>
Confrim Number <input type="text" name="delete_confNo" size="20">
ID             <input type="text" name="delete_idNo" size="20">
</pre>
<input type="submit" value="delete" name="deleteReservation">
</form>

<p>Search the available rooms:</p>
<form method="POST" action="customer_reservation.php">
<pre>
From Date <input type="text" name="from_date" size="20">
To Date   <input type="text" name="to_date" size="20">
</pre>
<input type="submit" value="search" name="empty_room">
</form>
    
<?php
    
    //this tells the system that it's no longer just parsing
    //html; it's now parsing PHP
    
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
    
    function printResult_Reservation($result) { //prints results from a select statement
        echo "<br>Got data from table Reservation:<br>";
        echo "<table>";
        echo "<tr>
        <th>confNo</th>
        <th>Branch ID</th>
        <th>roomTypeName</th>
        <th>idNo</th>
        <th>fromDate</th>
        <th>toDate</th>
        </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    
    function printResult_Order($result) { //prints results from a select statement
        echo "<br>Order:<br>";
        echo "<table>";
        echo "<tr>
            <th>Order Number</th>
            <th>Customer ID</th>
            <th>Branch ID</th>
             <th>Room Number</th>
            <th>From</th>
            <th>To</th>
            <th>Deposit</th>
            </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[2] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
    }
    
    function printResult_popularity($result) {
        echo "<table>";
        echo "<tr><th>Room Type</th><th>Popularity</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_emptyRoom($result) {
        echo "<br>Empty Rooms:<br>";
        echo "<table>";
        echo "<tr><th>Room Number</th>
        <th>Left</th>
        </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            </tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_averageRating($result) {
        echo "<table>";
        echo "<tr>
        <th>Room Type</th>
        <th>Average Rating</th>
        </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            </tr>";
        }
        echo "</table>";
        
    }
    
    // Connect Oracle...
    if ($db_conn) {
     if (array_key_exists('createReservation', $_POST)) {
                $tuple = array (
                                    ":bind1" => $_POST['create_confNo'],
                                    ":bind2" => $_POST['create_bid'],
                                    ":bind3" => $_POST['create_roomTypeName'],
                                    ":bind4" => $_POST['create_idNo'],
                                    ":bind5" => $_POST['create_fromDate'],
                                    ":bind6" => $_POST['create_toDate']);
    $alltuples = array ($tuple);
    executeBoundSQL("INSERT INTO Reservation VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
    OCICommit($db_conn);
    } else
        
    if (array_key_exists('deleteReservation', $_POST)) {
                $tuple = array (
                                        ":bind1" => $_POST['delete_confNo'],
                                        ":bind2" => $_POST['delete_idNo']);
    $alltuples = array ($tuple);
    executeBoundSQL("DELETE FROM Reservation WHERE confno = :bind1 and idno = :bind2", $alltuples);
    OCICommit($db_conn);
    } else
    
    if (array_key_exists('empty_room', $_POST)) {
        $aaa = $_POST['from_date'];
        $bbb = $_POST['to_date'];
        $result = executePlainSQL(
                                "select roomtypename, count(roomno)
                                 from (SELECT roomno, roomtypename FROM Room WHERE roomno NOT IN (SELECT roomno FROM orderInfo WHERE (fromDate <= '".$aaa."' AND toDate >= '".$aaa."') OR (fromDate <= '".$bbb."' AND toDate >= '".$bbb."') OR (fromDate >= '".$aaa."' AND toDate <= '".$bbb."')))
                                 group by roomtypename");
        printResult_emptyRoom($result);
        OCICommit($db_conn);
        }
        
        if($_POST && !$success) {echo "Failed";}
        else {
        $result1 = executePlainSQL(
                                "SELECT roomtypename, avg(rating) FROM Review group by roomtypename");
        printResult_averageRating($result1);
        $result2 = executePlainSQL(
                                "SELECT room.roomtypename, count(orderinfo.orderno) FROM OrderInfo, Room where orderInfo.roomno = room.roomno GROUP BY roomtypename");
        printResult_popularity($result2);
        }
        //Commit to save changes...
        OCILogoff($db_conn);
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
    
    /* OCILogon() allows you to log onto the Oracle database
     The three arguments are the username, password, and database.
     You will need to replace "username" and "password" for this to
     to work.
     all strings that start with "$" are variables; they are created
     implicitly by appearing on the left hand side of an assignment
     statement */
    /* OCIParse() Prepares Oracle statement for execution
     The two arguments are the connection and SQL query. */
    /* OCIExecute() executes a previously parsed statement
     The two arguments are the statement which is a valid OCI
     statement identifier, and the mode.
     default mode is OCI_COMMIT_ON_SUCCESS. Statement is
     automatically committed after OCIExecute() call when using this
     mode.
     Here we use OCI_DEFAULT. Statement is not committed
     automatically when using this mode. */
    /* OCI_Fetch_Array() Returns the next row from the result data as an
     associative or numeric array, or both.
     The two arguments are a valid OCI statement identifier, and an
     optinal second parameter which can be any combination of the
     following constants:
     
     OCI_BOTH - return an array with both associative and numeric
     indices (the same as OCI_ASSOC + OCI_NUM). This is the default
     behavior.
     OCI_ASSOC - return an associative array (as OCI_Fetch_Assoc()
     works).
     OCI_NUM - return a numeric array, (as OCI_Fetch_Row() works).
     OCI_RETURN_NULLS - create empty elements for the NULL fields.
     OCI_RETURN_LOBS - return the value of a LOB of the descriptor.
     Default mode is OCI_BOTH.  */
    ?>


