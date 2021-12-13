<?php
include_once('connection.php');

$countOfficials = "SELECT * FROM officials;";
$officialsQuery = mysqli_query($conn, $countOfficials);
$rowOfficials = mysqli_num_rows($officialsQuery);

$countResidents = "SELECT * FROM residents;";
$residentsQuery = mysqli_query($conn, $countResidents);
$rowResidents = mysqli_num_rows($residentsQuery);

$countVoters = "SELECT * FROM residents WHERE voterStatus = 'Yes';";
$VotersQuery = mysqli_query($conn, $countVoters);
$rowVoters = mysqli_num_rows($VotersQuery);

session_start();
$_SESSION['officials'] = $rowOfficials;
$_SESSION['residents'] = $rowResidents;
$_SESSION['voters'] = $rowVoters;

if (isset($_POST['btnLogin'])) {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];
    $loginQuery = "SELECT * FROM officials where (email = '$adminUsername' OR username = '$adminUsername') AND officialPassword = '$adminPassword';";
    $login = mysqli_query($conn, $loginQuery);
    
    if ($adminUsername == "admin" && $adminPassword == "admin") {
        $name = "ADMIN NAME";
        $purok = "ADMIN PUROK";
        $position = "ADMIN POSITION";        
        $imageLocation = "../assets/images/sample.jpg";
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['purok'] = $purok;
        $_SESSION['position'] = $position;
        $_SESSION['imageLocation'] = $imageLocation;
        header("Location: ../registerOfficial.php");
    }

    if (mysqli_num_rows($login) == 1) {
        while ($dashboard = mysqli_fetch_assoc($login)) {
            $name = $dashboard['nameAlias'];
            $position = $dashboard['position'];
            $purok = $dashboard['purok'];
            $image = $dashboard['imageLocation'];
        }
        session_start();
        $_SESSION['imageLocation'] = $image;
        $_SESSION['name'] = $name;
        $_SESSION['position'] = $position;
        $_SESSION['purok'] = $purok;
        header("Location: ../dashboard.php");
    }
}

if (isset($_POST['btnChangePass'])) {
    $username = $_POST['uName'];
    $password = $_POST['newPsswrd'];
    $changePassword = "UPDATE officials SET (username = '$username' OR email = '$username') AND officialPassword = '$password';";
    mysqli_query($conn, $changePassword);
    header("Location: ../index.php");
}

if (isset($_POST['btnRegisterResident'])) {
    $residentID = date("Y") . "-" . substr(hexdec(uniqid()), 12) . date("s");
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $alias = $_POST['alias'];
    $birthMonth = $_POST['bMonth'];
    $birthDay = $_POST['bDay'];
    $birthYear = $_POST['bYear'];
    $placeOfBirth = $_POST['pob'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['cStatus'];
    $voterStatus = $_POST['vStatus'];
    $ifActive = $_POST['voteActive'];
    $religion = $_POST['religion'];
    $nationality = $_POST['nationality'];
    $occupation = $_POST['occupation'];
    $sector = $_POST['sector'];
    $cityAddress = $_POST['cityAdd'];
    $provAddress = $_POST['provAdd'];
    $purok = $_POST['purok'];
    $email = $_POST['email'];
    $mobileNumberA = $_POST['mNumOne'];
    $mobileNumberB = $_POST['mNumTwo'];
    $houseNumberA = $_POST['hNumOne'];
    $houseNumberB = $_POST['hNumTwo'];
    $residentType = $_POST['resType'];
    $residentStatus = $_POST['resStat'];
    $encoder = $_POST['encoder'];
    $encoderPosition = $_POST['encoderPosition'];

    $file = $_FILES['imageResident'];
    $fileName = $_FILES['imageResident']['name'];
    $fileTmpName = $_FILES['imageResident']['tmp_name'];
    $fileError = $_FILES['imageResident']['error'];
    $fileType = $_FILES['imageResident']['type'];

    $fileExt = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExt));
    $allow = array('jpg', 'jpeg', 'png');

    if(in_array($fileExtension, $allow)){
        $fileNameNew = strtolower($residentID.$lastName) . '.' . $fileExtension;
        $filePath = './assets/images/residents/'.$fileNameNew;
        $sendToDirectory = '../assets/images/residents/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $sendToDirectory);
    }

    $insertToResident = "INSERT INTO residents (residentID, nameFirst, nameMiddle, nameLast, nameAlias, birthMonth, birthDay, birthYear, placeOB, gender, civilStatus, voterStatus, ifActive, religion, nationality, occupation, sector, cityAddress, provAddress, purok, email, mobileNumberA, mobileNumberB, homeNumberA, homeNumberB, residentType, residentStatus, encoder, encoderPosition, imageLocation)
        VALUES ('$residentID','$firstName', '$middleName', '$lastName', '$alias', '$birthMonth', '$birthDay', '$birthYear', '$placeOfBirth', '$gender', '$civilStatus', '$voterStatus', '$ifActive', '$religion', '$nationality', '$occupation', '$sector', '$cityAddress', '$provAddress', '$purok', '$email', '$mobileNumberA', '$mobileNumberB', '$houseNumberA', '$houseNumberB', '$residentType', '$residentStatus', '$encoder', '$encoderPosition', '$filePath');";
    mysqli_query($conn, $insertToResident);
    header("Location: ../residents.php");
}

if (isset($_POST['btnRegisterOfficial'])) {
    $idNumber = date("Y") . "-" . substr(hexdec(uniqid()), 12) . date("s");
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $alias = $_POST['alias'];
    $birthMonth = $_POST['bMonth'];
    $birthDay = $_POST['bDay'];
    $birthYear = $_POST['bYear'];
    $placeOfBirth = $_POST['pob'];
    $gender = $_POST['gender'];
    $civilStatus = $_POST['cStatus'];
    $position = $_POST['brgyPosition'];
    $cityAddress = $_POST['cityAdd'];
    $provAddress = $_POST['provAdd'];
    $purok = $_POST['purok'];
    $mobileNumberA = $_POST['mNumOne'];
    $mobileNumberB = $_POST['mNumTwo'];
    $houseNumberA = $_POST['hNumOne'];
    $houseNumberB = $_POST['hNumTwo'];
    $username = $_POST['uName'];
    $email = $_POST['email'];
    $password = $_POST['psswrd'];

    $file = $_FILES['official'];
    $fileName = $_FILES['official']['name'];
    $fileTmpName = $_FILES['official']['tmp_name'];
    $fileError = $_FILES['official']['error'];
    $fileType = $_FILES['official']['type'];

    $fileExt = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExt));
    $allow = array('jpg', 'jpeg', 'png');

    if(in_array($fileExtension, $allow)){
        $fileNameNew = strtolower($idNumber.$lastName) . '.' . $fileExtension;
        $filePath = './assets/images/officials/'.$fileNameNew;
        $sendToDirectory = '../assets/images/officials/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $sendToDirectory);
    }

    $insertToOfficials = "INSERT INTO officials (idNumber, nameLast, nameFirst, nameMiddle, nameAlias, birthMonth, birthDay, birthYear, placeOB, gender, civilStatus, position, cityAddress, provAddress, purok, mobileNumberA, mobileNumberB, homeNumberA, homeNumberB, email, username, officialPassword, imageLocation)
    VALUES ('$idNumber','$lastName','$firstName','$middleName','$alias','$birthMonth','$birthDay','$birthYear','$placeOfBirth','$gender','$civilStatus','$position','$cityAddress','$provAddress','$purok','$mobileNumberA','$mobileNumberB','$houseNumberA','$houseNumberB','$email','$username','$password', '$filePath');";
    mysqli_query($conn, $insertToOfficials);
    header("Location: ../dashboard.php");
}

if (isset($_POST['btnView'])) {
    $getID = $_POST['btnView'];

    $getQuery = "SELECT * FROM officials WHERE idNumber = '$getID';";
    $getOfficial = mysqli_query($conn, $getQuery);
    if (mysqli_num_rows($getOfficial) > 0) {
        while ($view = mysqli_fetch_assoc($getOfficial)) {
            $viewIdNumber = $view['idNumber'];
            $viewLastName = $view['nameLast'];
            $viewFirstName = $view['nameFirst'];
            $viewMiddleName = $view['nameMiddle'];
            $viewAlias = $view['nameAlias'];
            $viewMonth = $view['birthMonth'];
            $viewDay = $view['birthDay'];
            $viewYear = $view['birthYear'];
            $viewPOB = $view['placeOB'];
            $viewGender = $view['gender'];
            $viewCivilStatus = $view['civilStatus'];
            $viewPosition = $view['position'];
            $viewCityAddress = $view['cityAddress'];
            $viewProvAddress = $view['provAddress'];
            $viewPurok = $view['purok'];
            $viewMobileNumberA = $view['mobileNumberA'];
            $viewMobileNumberB = $view['mobileNumberB'];
            $viewHomeNumberA = $view['homeNumberA'];
            $viewHomeNumberB = $view['homeNumberB'];
            $viewEmail = $view['email'];
            $viewUsername = $view['username'];
            $viewPassword = $view['officialPassword'];
            $viewImage = $view['imageLocation'];
        }

        session_start();
        $_SESSION['getID'] = $viewIdNumber;
        $_SESSION['getLastName'] = $viewLastName;
        $_SESSION['getFirstName'] = $viewFirstName;
        $_SESSION['getMiddleName'] = $viewMiddleName;
        $_SESSION['getAlias'] = $viewAlias;
        $_SESSION['getMonth'] = $viewMonth;
        $_SESSION['getDay'] = $viewDay;
        $_SESSION['getYear'] = $viewYear;
        $_SESSION['getPOB'] = $viewPOB;
        $_SESSION['getGender'] = $viewGender;
        $_SESSION['getCivilStatus'] = $viewCivilStatus;
        $_SESSION['getPosition'] = $viewPosition;
        $_SESSION['getCity'] = $viewCityAddress;
        $_SESSION['getProv'] = $viewProvAddress;
        $_SESSION['getPurok'] = $viewPurok;
        $_SESSION['getMobileA'] = $viewMobileNumberA;
        $_SESSION['getMobileB'] = $viewMobileNumberB;
        $_SESSION['getHomeA'] = $viewHomeNumberA;
        $_SESSION['getHomeB'] = $viewHomeNumberB;
        $_SESSION['getEmail'] = $viewEmail;
        $_SESSION['getUsername'] = $viewUsername;
        $_SESSION['getPassword'] = $viewPassword;
        $_SESSION['viewImage'] = $viewImage;
        header("Location: ../viewUser.php");
        //session results to viewUser.php
    }
}

if (isset($_POST['btnViewResident'])) {
    $getResidentID = $_POST['btnViewResident'];

    $getQuery = "SELECT * FROM residents WHERE residentID = '$getResidentID';";
    $getResidents = mysqli_query($conn, $getQuery);
    if (mysqli_num_rows($getResidents) > 0) {
        while ($view = mysqli_fetch_assoc($getResidents)) {
            $viewIdNumber = $view['residentID'];
            $viewLastName = $view['nameLast'];
            $viewFirstName = $view['nameFirst'];
            $viewMiddleName = $view['nameMiddle'];
            $viewAlias = $view['nameAlias'];
            $viewMonth = $view['birthMonth'];
            $viewDay = $view['birthDay'];
            $viewYear = $view['birthYear'];
            $viewPOB = $view['placeOB'];
            $viewGender = $view['gender'];
            $viewCivilStatus = $view['civilStatus'];
            $viewVoterStatus = $view['voterStatus'];
            $viewIfActive = $view['ifActive'];
            $viewReligion = $view['religion'];
            $viewNationality = $view['nationality'];
            $viewOccupation = $view['occupation'];
            $viewSector = $view['sector'];
            $viewCityAddress = $view['cityAddress'];
            $viewProvAddress = $view['provAddress'];
            $viewPurok = $view['purok'];
            $viewEmail = $view['email'];
            $viewMobileNumberA = $view['mobileNumberA'];
            $viewMobileNumberB = $view['mobileNumberB'];
            $viewHomeNumberA = $view['homeNumberA'];
            $viewHomeNumberB = $view['homeNumberB'];
            $viewResidentType = $view['residentType'];
            $viewResidentStatus = $view['residentStatus'];
            $viewEncoder = $view['encoder'];
            $viewEncoderPosition = $view['encoderPosition'];
            $viewImage = $view['imageLocation'];
        }
        session_start();
        $_SESSION['viewIdNumber'] = $viewIdNumber;
        $_SESSION['viewLastName'] = $viewLastName;
        $_SESSION['viewFirstName'] = $viewFirstName;
        $_SESSION['viewMiddleName'] = $viewMiddleName;
        $_SESSION['viewAlias'] = $viewAlias;
        $_SESSION['viewMonth'] = $viewMonth;
        $_SESSION['viewDay'] = $viewDay;
        $_SESSION['viewYear'] = $viewYear;
        $_SESSION['viewPOB'] = $viewPOB;
        $_SESSION['viewGender'] = $viewGender;
        $_SESSION['viewCivilStatus'] = $viewCivilStatus;
        $_SESSION['viewVoterStatus'] = $viewVoterStatus;
        $_SESSION['viewIfActive'] = $viewIfActive;
        $_SESSION['viewReligion'] = $viewReligion;
        $_SESSION['viewNationality'] = $viewNationality;
        $_SESSION['viewOccupation'] = $viewOccupation;
        $_SESSION['viewSector'] = $viewSector;
        $_SESSION['viewCityAddress'] = $viewCityAddress;
        $_SESSION['viewProvAddress'] = $viewProvAddress;
        $_SESSION['viewPurok'] = $viewPurok;
        $_SESSION['viewEmail'] = $viewEmail;
        $_SESSION['viewMobileNumberA'] = $viewMobileNumberA;
        $_SESSION['viewMobileNumberB'] = $viewMobileNumberB;
        $_SESSION['viewHomeNumberA']  = $viewHomeNumberA;
        $_SESSION['viewHomeNumberB'] = $viewHomeNumberB;
        $_SESSION['viewResidentType'] = $viewResidentType;
        $_SESSION['viewResidentStatus'] = $viewResidentStatus;
        $_SESSION['viewEncoder'] = $viewEncoder;
        $_SESSION['viewEncoderPosition'] = $viewEncoderPosition;
        $_SESSION['viewImageLocation'] = $viewImage;
        header("Location: ../viewResident.php");
    }
}

if (isset($_POST['btnEdit'])) {
    //CODES HERE
}

if (isset($_POST['btnDelete'])) {
    $getID = $_POST['btnDelete'];

    $deleteOfficial = "DELETE FROM officials WHERE idNumber = '$getID';";
    mysqli_query($conn, $deleteOfficial);
    header("Location: ../dashboard.php");
}

if (isset($_POST['btnSaveEdit'])) {
    $idNumber = $_POST['resID'];
    $lastName = $_POST['lastName'];
    $civilStatus = $_POST['cStatus'];
    $voterStatus = $_POST['vStatus'];
    $voteActive = $_POST['voteActive'];
    $religion = $_POST['religion'];
    $nationality = $_POST['nationality'];
    $occupation = $_POST['occupation'];
    $sector = $_POST['sector'];
    $cityAddress = $_POST['cityAdd'];
    $provAddress = $_POST['provAdd'];
    $purok = $_POST['purok'];
    $email = $_POST['email'];
    $mobileNumberA = $_POST['mNumOne'];
    $mobileNumberB = $_POST['mNumTwo'];
    $homeNumberA = $_POST['hNumOne'];
    $homeNumberB = $_POST['hNumTwo'];
    $residentType = $_POST['resType'];
    $residentStatus = $_POST['resStat'];

    $file = $_FILES['viewResident'];
    $fileName = $_FILES['viewResident']['name'];
    $fileTmpName = $_FILES['viewResident']['tmp_name'];
    $fileError = $_FILES['viewResident']['error'];
    $fileType = $_FILES['viewResident']['type'];

    $fileExt = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExt));
    $allow = array('jpg', 'jpeg', 'png');

    if(in_array($fileExtension, $allow)){
        $fileNameNew = strtolower($idNumber.$lastName) . '.' . $fileExtension;
        $filePath = './assets/images/residents/'.$fileNameNew;
        $saveToDirectory = '../assets/images/residents/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $saveToDirectory);
    }

    $editQuery = "UPDATE residents SET civilStatus = '$civilStatus', voterStatus = '$voterStatus', ifActive = '$voteActive', religion = '$religion', 
    nationality = '$nationality', occupation = '$occupation', sector = '$sector', cityAddress = '$cityAddress', provAddress = '$provAddress', purok = '$purok', 
    email = '$email', mobileNumberA = '$mobileNumberA', mobileNumberB = '$mobileNumberB', homeNumberA = '$homeNumberA', residentType = '$residentType', residentStatus = '$residentStatus', imageLocation = '$filePath'
    WHERE residentID = '$idNumber';";
    mysqli_query($conn, $editQuery);
    header("Location: ../residents.php");
}

if (isset($_POST['btnDeleteResident'])) {
    $getID = $_POST['btnDeleteResident'];

    $deleteResident = "DELETE FROM residents WHERE residentID = '$getID';";
    mysqli_query($conn, $deleteResident);
    header("Location: ../residents.php");
}

if(isset($_POST['btnDownloadCert'])){
    $getId = $_POST['btnDownloadCert'];
    $getQuery = "SELECT * FROM residents WHERE residentID = '$getId';";
    $getResidents = mysqli_query($conn, $getQuery);
    if (mysqli_num_rows($getResidents) > 0) {
        while ($view = mysqli_fetch_assoc($getResidents)) {
            $viewIdNumber = $view['residentID'];
            $viewLastName = $view['nameLast'];
            $viewFirstName = $view['nameFirst'];
            $viewMiddleName = $view['nameMiddle'];
            $viewAlias = $view['nameAlias'];
            $viewMonth = $view['birthMonth'];
            $viewDay = $view['birthDay'];
            $viewYear = $view['birthYear'];
            $viewPOB = $view['placeOB'];
            $viewGender = $view['gender'];
            $viewCivilStatus = $view['civilStatus'];
            $viewVoterStatus = $view['voterStatus'];
            $viewIfActive = $view['ifActive'];
            $viewReligion = $view['religion'];
            $viewNationality = $view['nationality'];
            $viewOccupation = $view['occupation'];
            $viewSector = $view['sector'];
            $viewCityAddress = $view['cityAddress'];
            $viewProvAddress = $view['provAddress'];
            $viewPurok = $view['purok'];
            $viewEmail = $view['email'];
            $viewMobileNumberA = $view['mobileNumberA'];
            $viewMobileNumberB = $view['mobileNumberB'];
            $viewHomeNumberA = $view['homeNumberA'];
            $viewHomeNumberB = $view['homeNumberB'];
            $viewResidentType = $view['residentType'];
            $viewResidentStatus = $view['residentStatus'];
            $viewEncoder = $view['encoder'];
            $viewEncoderPosition = $view['encoderPosition'];
        }
        session_start();
        $_SESSION['PDFIdNumber'] = $viewIdNumber;
        $_SESSION['PDFLastName'] = $viewLastName;
        $_SESSION['PDFFirstName'] = $viewFirstName;
        $_SESSION['PDFMiddleName'] = $viewMiddleName;
        $_SESSION['PDFAlias'] = $viewAlias;
        $_SESSION['PDFMonth'] = $viewMonth;
        $_SESSION['PDFDay'] = $viewDay;
        $_SESSION['PDFYear'] = $viewYear;
        $_SESSION['PDFPOB'] = $viewPOB;
        $_SESSION['PDFGender'] = $viewGender;
        $_SESSION['PDFCivilStatus'] = $viewCivilStatus;
        $_SESSION['PDFVoterStatus'] = $viewVoterStatus;
        $_SESSION['PDFIfActive'] = $viewIfActive;
        $_SESSION['PDFReligion'] = $viewReligion;
        $_SESSION['PDFNationality'] = $viewNationality;
        $_SESSION['PDFOccupation'] = $viewOccupation;
        $_SESSION['PDFSector'] = $viewSector;
        $_SESSION['PDFCityAddress'] = $viewCityAddress;
        $_SESSION['PDFProvAddress'] = $viewProvAddress;
        $_SESSION['PDFPurok'] = $viewPurok;
        $_SESSION['PDFEmail'] = $viewEmail;
        $_SESSION['PDFMobileNumberA'] = $viewMobileNumberA;
        $_SESSION['PDFMobileNumberB'] = $viewMobileNumberB;
        $_SESSION['PDFHomeNumberA']  = $viewHomeNumberA;
        $_SESSION['PDFHomeNumberB'] = $viewHomeNumberB;
        $_SESSION['PDFResidentType'] = $viewResidentType;
        $_SESSION['PDFResidentStatus'] = $viewResidentStatus;
        $_SESSION['PDFEncoder'] = $viewEncoder;
        $_SESSION['PDFEncoderPosition'] = $viewEncoderPosition;
        header("Location: ../certification.php");
    }
}