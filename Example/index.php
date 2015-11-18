<?php
if($_GET['debug'] == true) $debug = TRUE;

//configuration file that holds our credentials
require_once("./config.php");

//autoloader for EdFi-API-PHP that will load all our classes for us
require_once("../autoloader.php");

//////////////////////

echo "<p>Authenticate: ";
echo '<pre>$$client = new \EdFi\Client(CLIENT_ID, CLIENT_SECRET);</pre></p>';   

$client = new \EdFi\Client(CLIENT_ID, CLIENT_SECRET);

echo "<p>Token: " . $client->getAccessToken() . "</p>";

//////////////////////

echo "<p>Find all students: ";
echo '<pre>$students = new \EdFi\Model\Students($client);<br>';
echo '$students = $students->getStudents();</pre></p>';
    
if(!$debug){    
    $students = new \EdFi\Model\Students($client);
    $students = $students->getStudents();
}

//////////////////////

echo "<p>Find a student: ";
echo '<pre>$student = new \EdFi\Model\Students($client);<br>';
echo '$student = $student->setId("1001332768"); //studentUniqueId</pre></p>';

if(!$debug){    
    $student = new \EdFi\Model\Students($client);
    $student = $student->getStudent("1001332768"); //studentUniqueId
}

//////////////////////

$studentData = array(
    //"id" => "123456", //dont need this, its the GUID for the edfi stuff
    "studentUniqueId"=> "1001332768",
    "firstName"=> "Jim",
    "lastSurname"=> "Halpert",
    "sexType"=> "Male",
    "economicDisadvantaged"=> false,
    "schoolFoodServiceEligibilityDescriptor"=> "31a40355c512424b9e3d5e9ee8f02b62",
    "birthDate"=> "06-19-1988",
    "characteristics" => array("descriptor" => "SATA"),
    "limitedEnglishProficiencyDescriptor" => "7",
    "hispanicLatinoEthnicity"=> false,
    "races"=> [
        array("raceType" => "white"),
    ]
);

echo "<p>Create a new student: <pre>";
echo '$studentData = array(
    "studentUniqueId"=> "1001332768",
    "firstName"=> "Jim",
    "lastSurname"=> "Halpert",
    "sexType"=> "Male",
    "economicDisadvantaged"=> false,
    "schoolFoodServiceEligibilityDescriptor"=> "31a40355c512424b9e3d5e9ee8f02b62",
    "birthDate"=> "06-19-1988",
    "characteristics" => array("descriptor" => "SATA"),
    "limitedEnglishProficiencyDescriptor" => "7",
    "hispanicLatinoEthnicity"=> false,
    "races"=> [
        array("raceType" => "white"),
    ]
);<br>';
echo '$student = new \EdFi\Model\Students($client, $studentData);<br>';
echo '$student->save();</pre></p>';

if(!$debug){    
    $student = new \EdFi\Model\Students($client, $studentData);
    $student->save();
}

/////////////////////

echo '<p>Update a students data: <pre>';
echo '$students = new \EdFi\Model\Students($client);<br>';
echo '$student = $students->getStudent("1001332768"); //studentUniqueId<br>';
echo '$student->firstName = "Jim";<br>';
echo '$student->save();';
echo '</pre></p>';

if(!$debug){    
    $students = new \EdFi\Model\Students($client);
    $student = $students->getStudent("1001332768"); //studentUniqueId

    $student->firstName = "Jim"; //update the property of that student
    $student->save(); //save will translate to update since the student is already created
}

/////////////////////

echo '<p>Delete a student: <pre>';
echo '$students = new \EdFi\Model\Students($client);<br>';
echo '$student = $students->getStudent("1001332768"); //studentUniqueId <br>';
echo '$student->delete();';
echo '</pre></p>';

if(!$debug){    
    $students = new \EdFi\Model\Students($client);
    $student = $students->getStudent("1001332768");  //studentUniqueId
    $student->delete(); //not working yet, getting some odd error about dependencies
}

///////////////////

echo '<p>Get all student/school associations: <pre>';
echo '$ssa = new \EdFi\Model\StudentSchoolAssociations($client);<br>';
echo '$associations = $ssa->getAssociations();<br>';
echo '</pre></p>';

$ssa = new \EdFi\Model\StudentSchoolAssociations($client);
$associations = $ssa->getAssociations();

///////////////////

echo '<p>Get a specific student/school association: <pre>';
echo '$ssa = new \EdFi\Model\StudentSchoolAssociations($client);<br>';
echo '$associations = $ssa->getAssociations("1001332768", ");<br>';
echo '</pre></p>';

$ssa = new \EdFi\Model\StudentSchoolAssociations($client);
$associations = $ssa->getAssociations();


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