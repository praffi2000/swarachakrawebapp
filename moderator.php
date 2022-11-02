<?php
    include 'config.php';
    date_default_timezone_set("Asia/Bangkok");
    $ucode = "aaaaa";//$date = "all";
    if (isset($_GET['ucode'])) {
        $ucode = $_GET['ucode'];
    }
    if(strlen($ucode)==0)
        $ucode = "aaaaa";

    $conn = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

    if (!$conn) {
        die("Database connection failed: " . mysqli_error());
    }

    mysqli_set_charset($conn, "utf8");
    mysqli_select_db($conn, DB_DATABASE);

   /* if($date === "all")
        $query = "SELECT id,sessionID,sessionDate,dependentVariable,phraseNumber,language,phraseShown,phraseTyped,editdistance,typingTime,round((char_length(phraseTyped)-1)/(typingTime/60000.0)) cpm, least(char_length(phraseTyped),char_length(phraseShown)) minLen, greatest(char_length(phraseTyped),char_length(phraseShown)) maxLen FROM experiment1 ORDER BY id desc";
    else*/
    $query = "SELECT id,substring(sessionID,7,2) sessionCode,sessionDate,dependentVariable,phraseNumber,language,phraseShown,phraseTyped,editdistance,typingTime,round((char_length(phraseTyped)-1)/(typingTime/60000.0)) cpm, least(char_length(phraseTyped),char_length(phraseShown)) minLen, greatest(char_length(phraseTyped),char_length(phraseShown)) maxLen FROM experiment1 WHERE sessionID LIKE '".$ucode."%' ORDER BY id desc";
    //echo $query;

    $result = mysqli_query($conn, $query);
    if ($result === false)
        die("Query failed: " . mysqli_error().$query);
   
   
    $tablePrefix = "<table><tr><th colspan='1'>Row ID</th><th colspan='1'>Session date</th><th colspan='1'>Session code</th><th colspan='1'>Variable</th><th colspan='1'>Phrase#</th><th colspan='1'>Shown phrase</th><th colspan='1'>Typed phrase</th><th colspan='1'>Error rate</th><th colspan='1'>Speed(cpm)</th></tr>";
    $tableSuffix = "</table>";
    $tableBody="";


    while ($row = mysqli_fetch_array($result)) {

        $timeInMins = ($row[9]/60000.0);
        $cpm=0;
        $minStrLen = $row[11];//min(mb_strlen($row[6]),mb_strlen($row[7]));
        $maxStrLen =  $row[12];//max(mb_strlen($row[6]),mb_strlen($row[7]));
        /*console.log("Strs:".$row[6].",".$row[7]);
        console.log($minStrLen);
        console.log($maxStrLen);*/
        

        //cpm
        /*if($timeInMins>0)
            $cpm = round(((mb_strlen($row[7])/3)-1)/$timeInMins,0);*/
            
        $cpm =  $row[10];

        //error rate
        if($row[8]>$minStrLen)
            $errorrate = 100;
        else
            $errorrate = round(($row[8]/$maxStrLen )*100,2);
       
        $tableBody.="\n<tr>";
        $tableBody.="<td colspan='1'>" . $row[0]. "</td>";
        $tableBody.="<td colspan='1'>" . $row[2] . "</td>";
        $tableBody.="<td colspan='1'>" . $row[1] . "</td>";        
        $tableBody.="<td colspan='1'>" . $row[3] . "</td>";
        $tableBody.="<td colspan='1'>" . $row[4] . "</td>";
/*        $tableBody.="<td colspan='1'>" . $row[5] . "</td>";*/
        $tableBody.="<td colspan='1'>" . $row[6] . "</td>";
        $tableBody.="<td colspan='1'>" . $row[7] . "</td>";
        $tableBody.="<td colspan='1'>" . $errorrate. "%</td>";
        //$tableBody.="<td colspan='1'>" . $row[8]. "</td>";
        //$tableBody.="<td colspan='1'>" . $row[9]. "</td>";
        $tableBody.="<td colspan='1'>" . $cpm. "</td>";
        $tableBody.="</tr>\n";        
    }
    
    mysqli_close($conn);
    echo $tablePrefix.$tableBody.$tableSuffix;


    ?>



