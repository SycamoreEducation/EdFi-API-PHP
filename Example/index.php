<?php
//configuration file that holds our credentials
require_once("./config.php");

//autoloader for EdFi-API-PHP that will load all our classes for us
require_once("../autoloader.php");

$client = new \EdFi\Client(CLIENT_ID, CLIENT_SECRET);

echo "<p>Token: " . $client->getAccessToken() . "</p>";


echo "<p>Find all students: ";
echo '<pre>$students = new \EdFi\Model\Students($client);<br>';
echo '$students = $students->getStudents();</pre></p>';
    
//$students = new \EdFi\Model\Students($client);
//$students = $students->getStudents();

echo "<p>Find a student: ";
echo '<pre>$student = new \EdFi\Model\Students($client);<br>';
echo '$student->setId("1001332768");</pre></p>';
    
//$student = new \EdFi\Model\Students($client);
//$student->getStudent("1001332768");

echo "<p>Create a connection between student and school: ";
echo '<pre>$student = new \EdFi\Model\Students($client, $studentData);<br>';
echo '$student->save();</pre></p>';

$studentData = array(
    //"id" => "123456", //dont need this, its the GUID for the edfi stuff
    "studentUniqueId"=> "1001332768",
    "firstName"=> "Brock",
    "lastSurname"=> "Ellis",
    "sexType"=> "Female",
    "economicDisadvantaged"=> false,
    "schoolFoodServiceEligibilityDescriptor"=> "31a40355c512424b9e3d5e9ee8f02b62",
    "birthDate"=> "06-19-1988",
    "characteristics" => array("descriptor" => "SATA"),
    "limitedEnglishProficiencyDescriptor" => "7",
    "hispanicLatinoEthnicity"=> false,
    "races"=> [
        array("raceType" => "white"),
        array("raceType" => "Black - African American")
    ]
);

//$student = new \EdFi\Model\Students($client, $studentData);
//$student->save();


$students = new \EdFi\Model\Students($client);
$students = $students->getStudents();

echo "<pre>" . print_r($students) . "</pre>";

$student = $students[0];

//print_r($student);








/*$studentSchoolAssociationData = array(
    "schoolReference" => array("schoolId" => "1100001001"),
    "studentReference" => array("studentUniqueId" => "1001332768"),
    "entryDate" => "09-01-2015",
    "entryTypeDescriptor" => "03",
    "exitWithdrawTypeDescriptor" => "BCA",
    "schoolYearTypeReference" => "5728c136f78c42cca7ab46659c3a9d19",
    "entryGradeLevelDescriptor" => "12",
    "residencyStatusDescriptor" => "01"
);*/

?>