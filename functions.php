<?php
/**
 * Created by PhpStorm.
 * User: Ishan Bhatt
 * Date: 9/28/16
 * Time: 8:42 PM
 */


//Retrieve information for all users
/**
 * @return array
 */
function fetchAllUsers() {
  global $mysqli, $db_table_prefix;
  $stmt = $mysqli->prepare(
    "SELECT
		id,
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active

		FROM " . $db_table_prefix . "user"
  );
  $stmt->execute();
  $stmt->bind_result(
    $id,
    $userid,
    $FirstName,
    $LastName,
    $City,
    $Zip,
    $DateOfBirth,
    $EmailAddress,
    $MemberSince,
    $active
  );

  while ($stmt->fetch()) {
    $row[] = array(
      'id'                      => $id,
      'userid'                  => $userid,
      'firstname'               => $FirstName,
      'lastname'                => $LastName,
      'city'                    => $City,
      'zip'                     => $Zip,
      'dateofbirth'             => $DateOfBirth,
      'email'                   => $EmailAddress,
      'membersince'             => $MemberSince,
      'active'                  => $active
    );
  }
  $stmt->close();
  return ($row);
}



//Create a new user

/**
 * @param $fname
 * @param $lname
 * @param $dob
 * @param $email
 * @param $city
 * @param $zip
 *
 * @return bool
 */
function createNewUser($fname, $lname, $dob, $email, $city, $zip) {
  global $mysqli, $db_table_prefix;
  //Generate A random userid
  $character_array = array_merge(range(a, z), range(0, 9));
  $rand_string = "";
  for ($i = 0; $i < 6; $i++) {
    $rand_string .= $character_array[rand(
      0, (count($character_array) - 1)
    )];
  }
 
  $stmt = $mysqli->prepare(
    "INSERT INTO " . $db_table_prefix . "user (
		userid,
		FirstName,
		LastName,
		City,
		Zip,
		DateOfBirth,
		EmailAddress,
		MemberSince,
		active
		)
		VALUES (
		'" . $rand_string . "',
		?,
		?,
		?,
		?,
		?,
		?,
        '" . time() . "',
        1
		)"
  );
  $stmt->bind_param("ssssss", $fname, $lname, $city, $zip, $dob, $email);
  $result = $stmt->execute();
  $stmt->close();
  return $result;

}

//fetch particular users record

/**
 * @param $userid
 *
 * @return array
 */
function fetchThisUser($userid) {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
      "
    SELECT
    id,
    userid,
    FirstName,
    LastName,
    DateOfBirth,
    EmailAddress,
    City,
    Zip,
    MemberSince,
    active

    FROM " . $db_table_prefix . "user
    WHERE
    userid = ?
    LIMIT 1"
    );
	
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $stmt->bind_result($id, $userid, $FirstName, $LastName, $DateOfBirth, $EmailAddress, $City, $Zip, $MemberSince, $active);
    $stmt->execute();
  while ($stmt->fetch()) {
    $row[] = array(
      'id'                      => $id,
      'userid'                  => $userid,
      'firstname'               => $FirstName,
      'lastname'                => $LastName,
      'city'                    => $City,
      'zip'                     => $Zip,
      'dateofbirth'             => $DateOfBirth,
      'email'                   => $EmailAddress,
      'membersince'             => $MemberSince,
      'active'                  => $active

    );
  }
  $stmt->close();
  return ($row);
}


//Update selected users record.
/**
 * @param $fname
 * @param $lname
 * @param $city
 * @param $zip
 * @param $dob
 * @param $email
 * @param $userid
 *
 * @return bool
 */
function updateThisRecord($fname, $lname, $city, $zip, $dob, $email, $userid) {
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare(
      "UPDATE " . $db_table_prefix . "user
		SET
		FirstName = ?,
		LastName = ?,
		City = ?,
		Zip = ?,
		DateOfBirth = ?,
		EmailAddress = ?
		WHERE
		userid = ?
		LIMIT 1"
    );
    $stmt->bind_param("sssssss", $fname, $lname, $city, $zip, $dob, $email, $userid);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
}