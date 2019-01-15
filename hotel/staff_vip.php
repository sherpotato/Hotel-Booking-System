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
<h1>VIP</h1>
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="staff.php">Staff</a></li>
<li><a href="staff_order.php">Order</a></li>
<li>VIP</li>
</ul>
</nav>
</header>



    
<p>Create an VIP account:</p>
<form method="POST" action="staff_vip.php">
<pre>
ID                    <input type="text" name="account1" size="20">
</pre>
<input type="submit" value="create" name="account">
</form>

<p>Update points for an VIP account:</p>
<form method="POST" action="staff_vip.php">
<pre>
ID                    <input type="text" name="points1" size="20">
Points                <input type="text" name="points2" size="20">
Level                 <input type="text" name="points3" size="20">
</pre>
<input type="submit" value="update" name="points">
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
    
    
    
    function printResult_vip($result) {
    echo "<p>VIP:</p>";
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
        if (array_key_exists('account', $_POST)) {
            $xxx = $_POST['account1'];
            executePlainSQL("INSERT INTO memberinfo VALUES('".$xxx."', '0','1')");
            OCICommit($db_conn);
    } else
        if (array_key_exists('points', $_POST)) {
            $a = $_POST['points1'];
            $b = $_POST['points2'];
            $c = $_POST['points3'];
            executePlainSQL("UPDATE memberinfo SET points = '".$b."' WHERE idno = '".$a."'");
            executePlainSQL("UPDATE memberinfo SET plevel = '".$c."' WHERE idno = '".$a."'");
            OCICommit($db_conn);
    }
            
    
                                                                                            
                                                                                            
    if ($_POST && !$success) {
        echo "failed";
    } else {
            // Select data...
        $kkk = executePlainSQL("SELECT * FROM staffvip");
        printResult_vip($kkk);
                                              }
                                                                                            
            //Commit to save changes...
    OCILogoff($db_conn);
    } else {
            echo "cannot connect";
        	$e = OCI_Error(); // For OCILogon errors pass no handle
        	echo htmlentities($e['message']);
    }
                                                                                            
    ?>
