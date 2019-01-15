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
<h1>PROFILE</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="customer.php">Customer</a></li>
<li><a href="customer_reservation.php">Reservation</a></li>
<li><a href="customer_review.php">Review</a></li>
<li>Profile</li>
</ul>
</nav>
</header>

<p>update your phone number:</p>
<form method="POST" action="customer_profile.php">
<pre>
ID     	  <input type="text" name="ins_idNo" size="20">
New Phone <input type="text" name="ins_phone" size="20">
</pre>
<input type="submit" value="update" name="updatephone">
</form>

<p>Check your information:</p>
<form method="POST" action="customer_profile.php">
<pre>
ID     <input type="text" name="vip_idNo" size="20">
</pre>
<input type="submit" value="check" name="vip_status">
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
    
    function printResult_status($result) { //prints results from a select statement
        echo "<table>";
        echo "<tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Birthday</th>
        <th>Points</th>
        <th>Level</th>
		<th>Discount</th>
        </tr>";
        
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr>
            <td>" . $row[0] . "</td>
            <td>" . $row[1] . "</td>
            <td>" . $row[2] . "</td>
            <td>" . $row[3] . "</td>
            <td>" . $row[4] . "</td>
            <td>" . $row[5] . "</td>
            <td>" . $row[6] . "</td>
            </tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
        
    }
    
    // Connect Oracle...
    if ($db_conn) {
    if (array_key_exists('updatephone', $_POST)) {
            $id = $_POST['ins_idNo'];
            $phone = $_POST['ins_phone'];
            $result = executePlainSQL("update hotelcustomer set phoneno = '".$phone."' where idno = '".$id."' ");
            OCICommit($db_conn);
        }
        
        if (array_key_exists('vip_status', $_POST)) {
            $input = $_POST['vip_idNo'];
            $result = executePlainSQL("SELECT hc.idNo,hc.name,hc.phoneno,hc.birthday, mi.points, mi.plevel, ld.discount
            FROM Hotelcustomer hc, memberInfo mi, level_discount ld
            WHERE hc.idNo = '".$input."' and hc.idno = mi.idno and mi.plevel = ld.plevel
            ");
            printResult_status($result);
            OCICommit($db_conn);
        }
    
        if ($_POST && !$success) {
            echo "Failed";
        } else {
            // Select data...
        }
        
        //Commit to save changes...
        OCILogoff($db_conn);
    } else {
        echo "cannot connect";
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
    }
    ?>


