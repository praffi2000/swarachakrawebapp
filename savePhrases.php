<?php

    include 'config.php';

	$id = "";
	$dependentVariable="var1";

	//$tapsJSON='[{"tapSequenceNumber":1,"startTimestamp":1531976733779,"endTimestamp":1531976733895},{"tapSequenceNumber":2,"startTimestamp":1531976734286,"endTimestamp":1531976734413},{"tapSequenceNumber":3,"startTimestamp":1531976734455,"endTimestamp":1531976734571},{"tapSequenceNumber":4,"startTimestamp":1531976734612,"endTimestamp":1531976734714},{"tapSequenceNumber":5,"startTimestamp":1531976734759,"endTimestamp":1531976734891},{"tapSequenceNumber":6,"startTimestamp":1531976734918,"endTimestamp":1531976735028},{"tapSequenceNumber":7,"startTimestamp":1531976735060,"endTimestamp":1531976735183},{"tapSequenceNumber":8,"startTimestamp":1531976863014,"endTimestamp":1531976863098}]';

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }else
    	$id = time();
    if (isset($_POST['var'])) {
        $dependentVariable = $_POST['var'];
    }

    if (isset($_POST['phrases'])) {

        $tapsJSON = $_POST['phrases'];

    }else{
    	echo "No phrase logs found";
    	return;
    }


    $taps = json_decode($tapsJSON);


    //echo "\n ID:".$id." dependent variable:".$dependentVariable;
    //print_r($taps);
    $insert_query = "INSERT INTO experiment1(sessionID,dependentVariable,phraseNumber,language,phraseShown,phraseTyped,editdistance,typingTime, keystrokes) VALUES ";
    $insert_body ="";

    for($i=0;$i<count($taps);$i++){

    	if(strlen($insert_body) >0)
    		$insert_body.= ",";

    	$insert_body.="('".$id."','".$dependentVariable."',".$taps[$i]->phraseSequenceNumber.",'".$taps[$i]->phraseLanguage."','".$taps[$i]->phraseShown."','".$taps[$i]->phraseTyped."',".$taps[$i]->editdistance.",".$taps[$i]->timeTaken.",'".$taps[$i]->ksLogs."')";

   	}

    $conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

    if (!$conn) {
        die("Database connection failed: " . mysqli_error());
    }

    mysqli_set_charset($conn, "utf8");
    mysqli_select_db($conn, DB_DATABASE);

    $result = mysqli_query($conn,$insert_query.$insert_body);

    if($result)
    	echo "Data saved successfully";
    else
    	die("Data could not be saved: " . mysqli_error($conn)." ".$insert_query.$insert_body);



?>