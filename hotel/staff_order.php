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
<h1>ORDER</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="staff.php">Staff</a></li>
<li>Order</li>
<li><a href="staff_vip.php">VIP</a></li>
</ul>
</nav>
</header>



    
<p>Create an Order:</p>
<form method="POST" action="staff_order.php">
<pre>
Order Number          <input type="text" name="order1" size="20">
Deposit               <input type="text" name="order2" size="20">
ID                    <input type="text" name="order3" size="20">
From Date             <input type="text" name="order4" size="20">
To Date               <input type="text" name="order5" size="20">
Branch ID             <input type="text" name="order6" size="20">
Room Number           <input type="text" name="order7" size="20">
Conformation Number   <input type="text" name="order8" size="20">
Check Out Time        <input type="text" name="order9" size="20">
Cost                  <input type="text" name="order10" size="20">
</pre>
<input type="submit" value="create" name="createOrder">
</form>

<p>Check Out:</p>
<form method="POST" action="staff_order.php">
<pre>
Room Number <input type="text" name="checkout1" size="20">
</pre>
<input type="submit" value="submit" name="checkout">
</form>

<p>Update to date for an Order:</p>
<form method="POST" action="staff_order.php">
<pre>
Order Number          <input type="text" name="toDate_1" size="20">
To Date               <input type="text" name="toDate_2" size="20">
</pre>
<input type="submit" value="update" name="updateToDate">
</form>

<p>Delete an Order:</p>
<form method="POST" action="staff_order.php">
<pre>
Order Number <input type="text" name="delete_order" size="20">
</pre>
<input type="submit" value="delete" name="deleteOrder">
</form>
    
<p>Get the real time total cost:</p>
<form method="POST" action="staff_order.php">
<pre>
Order Number <input type="text" name="order_number" size="20">
</pre>
<input type="submit" value="calculate" name="detail_cost">
</form>

<p>Update cost for an Order:</p>
<form method="POST" action="staff_order.php">
<pre>
Order Number          <input type="text" name="cost_1" size="20">
Cost                  <input type="text" name="cost_2" size="20">
</pre>
<input type="submit" value="update" name="updateCost">
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
        echo "<br>Reservation:<br>";
        echo "<table>";
        echo "<tr><th>Conformation Number</th><th>Branch Number</th><th>Room Type Name</th><th>ID</th><th>From Date</th><th>To Date</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    
    function printResult_HotelCustomer($result) { //prints results from a select statement
        echo "<br>Customer:<br>";
        echo "<table>";
        echo "<tr><th>idNo</th><th>phoneNo</th><th>name</th><th>birthday</th><th>gender</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
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
        echo "<tr><th>ID</th><th>Room Number</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_Order($result) {
        echo "<br>Order:<br>";
        echo "<table>";
        echo "<tr><th>Order Number</th><th>Deposit</th><th>ID</th><th>From Date</th><th>To Date</th><th>Branch</th><th>Room Number</th><th>Conformation Number</th><th>Check Out Time</th><th>Cost</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[7] . "</td><td>" . $row[8] . "</td><td>" . $row[9] . "</td></tr>";
        }
        echo "</table>";
    }
    
    function printResult_popularity($result) {
        echo "<br>Popularity:<br>";
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
        echo "<tr><th>Room Number</th></tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>";
        }
        echo "</table>";
        
    }
    
    function printResult_averageRating($result) {
        echo "<br>Average Rating:<br>";
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
    
    if (array_key_exists('checkout', $_POST)) {
    $input = $_POST['checkout1'];
    executePlainSQL("DELETE FROM roomService WHERE roomNo = '".$input."'");
    executePlainSQL("UPDATE Room SET availability = 'YES' WHERE roomNo = '".$input."'");
    OCICommit($db_conn);
    } else
    
    if (array_key_exists('createOrder', $_POST)) {
       $tuple = array (
                                        ":bind1" => $_POST['order1'],
                                        ":bind2" => $_POST['order2'],
                                        ":bind3" => $_POST['order3'],
                                        ":bind4" => $_POST['order4'],
                                        ":bind5" => $_POST['order5'],
                                        ":bind6" => $_POST['order6'],
                                        ":bind7" => $_POST['order7'],
                                        ":bind8" => $_POST['order8'],
                                        ":bind9" => $_POST['order9'],
                                        ":bind10" => $_POST['order10']);
    $alltuples = array ($tuple);
    executeBoundSQL("INSERT INTO OrderInfo VALUES(:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9, :bind10)", $alltuples);
    OCICommit($db_conn);
    } else
    
    if (array_key_exists('deleteOrder', $_POST)) {
                $tuple = array (
                                        ":bind1" => $_POST['delete_order']);
    $alltuples = array ($tuple);
    executeBoundSQL("DELETE FROM OrderInfo WHERE orderNo = :bind1" , $alltuples);
    OCICommit($db_conn);
    } else
    
    if (array_key_exists('updateToDate', $_POST)) {
                $tuple = array (
                                        ":bind1" => $_POST['toDate_1'],
                                        ":bind2" => $_POST['toDate_2']);
    $alltuples = array ($tuple);
    executeBoundSQL("UPDATE OrderInfo SET toDate = :bind2 WHERE orderNo = :bind1" , $alltuples);
    OCICommit($db_conn);
    } else
    
    if (array_key_exists('updateCost', $_POST)) {
                $tuple = array (
                                        ":bind1" => $_POST['cost_1'],
                                        ":bind2" => $_POST['cost_2']);
    $alltuples = array ($tuple);
    executeBoundSQL("UPDATE OrderInfo SET cost = :bind2 WHERE orderNo = :bind1" , $alltuples);
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
                                            "SELECT HotelCustomer.idno, room.roomno FROM Room, orderinfo, HotelCustomer WHERE HotelCustomer.idno = '".$input."'
                                            and room.roomno = orderinfo.roomno and orderinfo.idno = HotelCustomer.idno");
	printResult_roomNumber($result);
    OCICommit($db_conn);
    } else
                                                                                            
    if (array_key_exists('empty_room', $_POST)) {
    $aaa = $_POST['from_date'];
    $bbb = $_POST['to_date'];
    $result = executePlainSQL(
                            "SELECT roomno FROM Room WHERE roomno NOT IN (SELECT roomno FROM orderInfo WHERE (fromDate <= '".$aaa."' AND toDate >= '".$aaa."') OR (fromDate <= '".$bbb."' AND toDate >= '".$bbb."') OR (fromDate >= '".$aaa."' AND toDate <= '".$bbb."'))");
    printResult_emptyRoom($result);
    OCICommit($db_conn);
    }
                                                                                            
                                                                                            
    if ($_POST && !$success) {
        echo "failed";
    } else {
            // Select data...
            $result5 = executePlainSQL("SELECT * FROM orderinfo");
            printResult_Order($result5);
                                              }
                                                                                            
            //Commit to save changes...
    OCILogoff($db_conn);
    } else {
            echo "cannot connect";
        	$e = OCI_Error(); // For OCILogon errors pass no handle
        	echo htmlentities($e['message']);
    }
                                                                                            
    ?>
