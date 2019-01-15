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
<h1>CUSTOMER</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li>Customer</li>
<li><a href="customer_reservation.php">Reservation</a></li>
<li><a href="customer_review.php">Review</a></li>
<li><a href="customer_profile.php">Profile</a></li>
</ul>
</nav>
</header>

<p>Create a customer:</p>
<form method="POST" action="customer.php">
<pre>
ID number      <input type="text" name="create_idNo" size="20">
phone          <input type="text" name="create_phone" size="20">
name           <input type="text" name="create_name" size="20">
birthday       <input type="text" name="create_birthday" size="20">
gender         <input type="text" name="create_gender" size="20">
</pre>
<input type="submit" value="create" name="createCustomer">
</form>
    
    
<p>Get the room number for the customer with ID:</p>
<form method="POST" action="customer.php">
<pre>
ID <input type="text" name="customer_idNo" size="20">
</pre>
<input type="submit" value="search" name="room_number">
</form>

<p>Get the real time total cost:</p>
<form method="POST" action="customer.php">
<pre>
Order Number <input type="text" name="order_number" size="20">
</pre>
<input type="submit" value="calculate" name="detail_cost">
</form>
    
<p>Search the available facility:</p>
<form method="POST" action="customer.php">
<pre>
Branch ID <input type="text" name="bid" size="20">
From Time <input type="text" name="from_time" size="20">
To Time   <input type="text" name="to_time" size="20">
</pre>
<input type="submit" value="search" name="available_facility">
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
    
    
    function printResult_totalCost($result) {
        echo "<br>Total cost for the order number:<br>";
        echo "<table>";
        echo "<tr><th>Order Number</th><th>Total Cost</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_roomNumber($result) {
        echo "<br>Total cost for the order number:<br>";
        echo "<table>";
        echo "<tr>
            <th>ID</th>
            <th>Branch ID</th>
            <th>Room Number</th>
            </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            </tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_availableFacility($result) {
        echo "<table>";
        echo "<tr><th>Facility Name</th><th>From Time</th><th>To Time</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
        }
        echo "</table>";
        
    }
    
    // Connect Oracle...
    if ($db_conn) {
        if (array_key_exists('createCustomer', $_POST)) {
            $tuple = array (
                            ":bind1" => $_POST['create_idNo'],
                            ":bind2" => $_POST['create_phone'],
                            ":bind3" => $_POST['create_name'],
                            ":bind4" => $_POST['create_birthday'],
                            ":bind5" => $_POST['create_gender']);
            $alltuples = array ($tuple);
            executeBoundSQL("INSERT INTO HotelCustomer VALUES(:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
            OCICommit($db_conn);
        } else
        
         if (array_key_exists('detail_cost', $_POST)) {
            $input = $_POST['order_number'];
            $result = executePlainSQL("SELECT oi.orderNo, (price+sumprice)*staffvip.discount as cost
                                      FROM orderinfo oi,room r,typeinfo ti, typerate tr,
                                      (SELECT orderno, sum(roomserviceprice) AS sumprice
                                       FROM OrderInfo, RoomService, ServiceRate
                                       WHERE orderNo = '".$input."' and orderinfo.roomno = roomservice.roomno
                                     and orderinfo.bid = roomservice.bid
                                       and RoomService.RoomServiceName = ServiceRate.RoomServiceName
                                       GROUP BY orderNo) g, staffvip
                                       WHERE oi.roomno = r.roomno and
                                     oi.bid = r.bid and r.roomtypename = ti.roomtypename and ti.capacity = tr.capacity and oi.orderNo = g.orderno
                                     and oi.idno = staffvip.idno");
            printResult_totalCost($result);
            OCICommit($db_conn);
        } else
        
        if (array_key_exists('room_number', $_POST)) {
           $input = $_POST['customer_idNo'];
           $result = executePlainSQL(
                                  "SELECT HotelCustomer.idno, room.bid, room.roomno FROM Room, orderinfo, HotelCustomer WHERE HotelCustomer.idno = '".$input."'
                                  and room.roomno = orderinfo.roomno and
                                  room.bid = orderinfo.bid and orderinfo.idno = HotelCustomer.idno");
           printResult_roomNumber($result);
           OCICommit($db_conn);
        } else
        
        if (array_key_exists('available_facility', $_POST)) {
        $bid = $_POST['bid'];
        $from = $_POST['from_time'];
        $to = $_POST['to_time'];
        $result = executePlainSQL(
                                "SELECT facilityName, fromTime, toTime FROM Facility WHERE bid ='".$bid."' and '".$from."' >= fromTime AND '".$to."' <= toTime");
        printResult_availableFacility($result);
        OCICommit($db_conn);
        }
        
        
//        if ($_POST && $success) {
//            //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
//            header("location: customer.php");
//        } else {
//            // Select data...
//        }
        if($_POST && !$success) {echo "Failed";}
        //Commit to save changes...
        OCILogoff($db_conn);
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
    

    ?>


