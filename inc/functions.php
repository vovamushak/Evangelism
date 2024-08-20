<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

define( 'f_DOMAIN', 'https://www.hopeforevangelism.com/evangel' );
date_default_timezone_set( 'UTC' );

function isEmailVerified( $conn, $verificationCode )
 {
    $query = "SELECT * FROM tb_users WHERE rcode='$verificationCode'";
    return mysqli_num_rows( mysqli_query( $conn, $query ) ) > 0;
}

function updateVerificationCode( $conn, $verificationCode )
 {
    $query = "UPDATE tb_users SET rcode='' WHERE rcode='$verificationCode'";
    return mysqli_query( $conn, $query );
}

function loginUser( $conn, $email, $password )
 {
    $hashedPassword = md5( $password );
    $query = "SELECT * FROM tb_users WHERE email='$email' AND password='$hashedPassword'";
    $result = mysqli_query( $conn, $query );

    if ( mysqli_num_rows( $result )  > 0 ) {
        $row = mysqli_fetch_assoc( $result );
        return $row;
    } else {
        return false;
    }
}

function isEmailExists( $conn, $email )
 {
    $query = "SELECT * FROM tb_users WHERE email='{$email}'";
    return mysqli_num_rows( mysqli_query( $conn, $query ) ) > 0;
}

function isUsernrExistsInMembers( $conn, $usernr )
 {
    $query = "SELECT * FROM tb_members WHERE usernr='{$usernr}'";
    return mysqli_num_rows( mysqli_query( $conn, $query ) ) > 0;
}

function isUserExistsInMembers( $conn, $usernr ) {
    $query = "SELECT * FROM tb_members WHERE usernr='{$usernr}'";
    return mysqli_fetch_assoc( mysqli_query( $conn, $query ) );
}

function getUsernrByEmail( $conn, $email ) {
    $query = "SELECT * FROM tb_users WHERE email='{$email}'";
    return mysqli_query( $conn, $query );
}

function registerUser( $conn, $name, $email, $password, $code )
 {
    $hashedpassword = md5( $password );
    $sql = "INSERT INTO tb_users (email, password, rcode, active, admin) VALUES ('{$email}', '{$hashedpassword}', '{$code}', '1', '0')";
    $res = mysqli_query( $conn, $sql );
    if ( $res ) {
        $usernr = $conn->insert_id;

        $query = "INSERT INTO tb_members (usernr,type, fullname, organization, street, zip, city, country, cellphone, telephone, instagram, facebook, website, active, sendout) VALUES ('{$usernr}', '', '{$name}', '', '','', '', '', '', '', '', '', '', '0', '0')";
        return mysqli_query( $conn, $query );
    } else {
        return false;
    }
}

// function registerMember( $conn, $usernr, $type, $fullname, $organization, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website )

function registerMember( $conn, $usernr, $type, $fullname, $organization, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website )
 {
    $query = "INSERT INTO tb_members (usernr, type, fullname, organization, street, zip, city, country, cellphone, telephone, instagram, facebook, website) VALUES ('{$usernr}', '{$type}', '{$fullname}', '{$organization}', '{$street}', '{$zip}', '{$city}', '{$country}', '{$cellphone}', '{$telephone}', '{$instagram}', '{$facebook}', '{$website}')";
    // $query = "INSERT INTO tb_members (type, fullname, organization, street, zip, city, country, cellphone, telephone, instagram, facebook, website) VALUES ('{$type}', '{$fullname}', '{$organization}', '{$street}', '{$zip}', '{$city}', '{$country}', '{$cellphone}', '{$telephone}', '{$instagram}', '{$facebook}', '{$website}')";
    $res = mysqli_query( $conn, $query );

    if ( $res ) {
        $query = "UPDATE tb_users SET active='1' WHERE usernr='$usernr'";

        return mysqli_query( $conn, $query );
    } else {
        return false;
    }
}

function get_email_txt( $conn, $type, $lang )
 {

    $query = "SELECT * FROM tb_email_text WHERE langu = '" . $lang . "' AND type = '" . $type . "' AND ewtype = 'email'";
    $row = mysqli_query( $conn, $query );
    if ( mysqli_num_rows( $row ) > 0 ) {
        return $row;
    } else {
        return mysqli_query( $conn, "SELECT * FROM tb_email_text WHERE langu = 'en' AND type = '" . $type . "' AND ewtype = 'email'" );
    }
}

function send_email( $conn, $email, $code, $for, $subject, $content, $lang )
 {
    $mail = new PHPMailer( true );

    try {
        // Server settings
        // send@hopeforevangelism.com
        // SMTP: send.one.com
        // Port: 465
        // Passwd: aB12!skou( 0_ns
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'send.one.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'send@hopeforevangelism.com';
        $mail->Password = 'aB12!skou(0_ns';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom( 'send@hopeforevangelism.com' );
        $mail->addAddress( $email );

        $mail->isHTML( true );

        $query = "SELECT * FROM tb_users JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.email = '{$email}';";
        $result = mysqli_fetch_assoc( mysqli_query( $conn, $query ) );

        $query = "SELECT * FROM tb_users JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.usernr = '{$code}'";
        $result1 = mysqli_fetch_assoc( mysqli_query( $conn, $query ) );

        if ( $for == 'reset_pwd' ) {
            $verifyContent = mysqli_fetch_assoc( get_email_txt( $conn, 'changepassword', $lang ) )[ 'txt' ];
            $verifyContent = str_replace( 'THEVERIFYLINK', 'https://www.hopeforevangelism.com/evangel/change-password.php?reset=' . $code, $verifyContent );
            $verifyContent = str_replace( 'REMOVEMELINK', 'https://www.hopeforevangelism.com/evangel/remove-me.php?usernr=' . $result[ 'usernr' ], $verifyContent );
            $mail->Subject = 'Reset password verfiy';
            $mail->Body = $verifyContent;
        } else if ( $for == 'verify' ) {
            $verifyContent = mysqli_fetch_assoc( get_email_txt( $conn, 'register', $lang ) )[ 'txt' ];
            $verifyContent = str_replace( 'THEVERIFYLINK', 'https://www.hopeforevangelism.com/evangel/?verification=' . $code, $verifyContent );
            $verifyContent = str_replace( 'REMOVEMELINK', 'https://www.hopeforevangelism.com/evangel/remove-me.php?usernr=' . $result[ 'usernr' ], $verifyContent );
            $mail->Subject = 'Register verfiy';
            $mail->Body = $verifyContent;
        } else if ( $for == 'connect' ) {
            $verifyContent = mysqli_fetch_assoc( get_email_txt( $conn, 'connect', $lang ) )[ 'txt' ];
            $verifyContent = str_replace( 'MEMBERDETAILSContent', 'Name: '.$subject[ 'fullname' ].', Street: '.$subject[ 'street' ].', City: '.$subject[ 'city' ].', Country: '.$subject[ 'country' ], $verifyContent );
            $verifyContent = str_replace( 'SHOWMEMBERDETAILS', 'https://www.hopeforevangelism.com/evangel/member-detail.php?usernr='.$subject[ 'usernr' ], $verifyContent );
            $verifyContent = str_replace( 'CONNECTTOMEMBER', 'https://www.hopeforevangelism.com/evangel/accept-connect.php?usernr='.$subject[ 'usernr' ], $verifyContent );
            $mail->Subject = 'Connect Request';
            $mail->Body = $verifyContent;
        } else if ( $for == 'add-convert' ) {
            $verifyContent = mysqli_fetch_assoc( get_email_txt( $conn, 'addconvert', $lang ) )[ 'txt' ];
            $verifyContent = str_replace( 'THEVERIFYLINK', 'https://www.hopeforevangelism.com/evangel/register.php', $verifyContent );
            $mail->Subject = 'You are added';
            $mail->Body = $verifyContent;
        } else {
            $verifyContent = mysqli_fetch_assoc( get_email_txt( $conn, 'sendemail', $lang ) )[ 'txt' ];
            $verifyContent = str_replace( 'SUBJECT', $subject, $verifyContent );
            $verifyContent = str_replace( 'MESSAGE', $content, $verifyContent );
            $verifyContent = str_replace( 'FROMWHO', 'Name: '.$result1[ 'fullname' ].', Zipcode: '. $result1[ 'zip' ] .', City: '.$result1[ 'city' ].', Country: '.$result1[ 'country' ], $verifyContent );
            $verifyContent = str_replace( 'SHOWMEMBERDETAILS', 'https://www.hopeforevangelism.com/evangel/member-detail.php?usernr='.$code, $verifyContent );
            $mail->Subject = $subject;
            $mail->Body = $verifyContent;
        }

        $mail->send();

        return true;
    } catch ( Exception $e ) {
        return false;
    }
}

function select_oneuserByEmail( $conn, $email )
 {
    $query = "SELECT * FROM tb_users WHERE tb_users.email = '{$email}'";
    return mysqli_query( $conn, $query );
}

function select_userByEmail( $conn, $email )
 {
    $query = "SELECT * FROM tb_users CROSS JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.email = '{$email}'";
    return mysqli_query( $conn, $query );
}

function select_userById( $conn, $id )
 {
    $query = "SELECT * FROM tb_users INNER JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.usernr = '{$id}'";
    return mysqli_query( $conn, $query );
}

function update_profile( $conn, $type, $fullname, $email, $organization, $password, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $whatsappcode, $usernr )
 {
    if ( $password == '' ) {
        $query = "UPDATE tb_users
        JOIN tb_members ON tb_users.usernr = tb_members.usernr
        SET 
            tb_users.email = '{$email}',
            tb_members.type = '{$type}',
            tb_members.fullname = '{$fullname}',
            tb_members.organization = '{$organization}',
            tb_members.street = '{$street}',
            tb_members.zip = '$zip',
            tb_members.city = '$city',
            tb_members.country = '$country',
            tb_members.cellphone = '$cellphone',
            tb_members.telephone = '$telephone',
            tb_members.instagram = '$instagram',
            tb_members.facebook = '$facebook',
            tb_members.website = '{$website}',
            tb_members.whatsappcode = '{$whatsappcode}',
            tb_members.active = 1
        WHERE 
            tb_users.usernr = '{$usernr}'";
        $res = mysqli_query( $conn, $query );

        if ( $res ) {
            $query = "UPDATE tb_users SET active='1' WHERE usernr='$usernr'";
            return mysqli_query( $conn, $query );
        } else {
            return false;
        }
    } else {
        $password = md5( $password );
        $query = "UPDATE tb_users
        JOIN tb_members ON tb_users.usernr = tb_members.usernr
        SET 
            tb_users.email = '{$email}',
            tb_users.password = '{$password}',
            tb_members.type = '{$type}',
            tb_members.fullname = '{$fullname}',
            tb_members.organization = '{$organization}',
            tb_members.street = '{$street}',
            tb_members.zip = '$zip',
            tb_members.city = '$city',
            tb_members.country = '$country',
            tb_members.cellphone = '$cellphone',
            tb_members.telephone = '$telephone',
            tb_members.instagram = '$instagram',
            tb_members.facebook = '$facebook',
            tb_members.website = '{$website}',
            tb_members.whatsappcode = '{$whatsappcode}',
            tb_members.active = 1
        WHERE 
            tb_users.usernr = '{$usernr}'";
        $res = mysqli_query( $conn, $query );

        if ( $res ) {
            $query = "UPDATE tb_users SET active='1' WHERE usernr='$usernr'";
            return mysqli_query( $conn, $query );
        } else {
            return false;
        }
    }
}

function create_event( $conn, $usernr, $name, $street, $zip, $city, $country, $dateofevent, $dateofeventEnd, $invitetxt, $radiuskm, $web )
 {
    $query = "INSERT INTO tb_event (usernr, name, street, zip, city, country, begindate, enddate, invitetxt, radiuskm, web, sendout)
    VALUES ('{$usernr}', '{$name}', '{$street}', '{$zip}', '{$city}', '{$country}', '{$dateofevent}', '{$dateofeventEnd}', '{$invitetxt}', '{$radiuskm}', '{$web}', 0);";
    return mysqli_query( $conn, $query );
}

function update_event( $conn, $eventnr, $name, $street, $zip, $city, $country, $dateofevent, $dateofeventEnd, $invitetxt, $radiuskm, $web, $facebook, $instagram ) {
    $query = "UPDATE tb_event
    SET 
        tb_event.name = '{$name}',
        tb_event.street = '{$street}',
        tb_event.zip = '{$zip}',
        tb_event.city = '{$city}',
        tb_event.country = '{$country}',
        tb_event.begindate = '{$dateofevent}',
        tb_event.enddate = '{$dateofeventEnd}',
        tb_event.invitetxt = '{$invitetxt}',
        tb_event.radiuskm = '{$radiuskm}',
        tb_event.web = '{$web}',
        tb_event.facebook = '{$facebook}',
        tb_event.instagram = '{$instagram}'
    WHERE 
        tb_event.eventnr = '{$eventnr}'";

    return mysqli_query( $conn, $query );
}

function read_event( $conn, $event_id )
 {
    $query = '';
    return mysqli_query( $conn, $query );
}

function delete_event( $conn, $event_id ) {
    $stmt = mysqli_prepare( $conn, 'DELETE FROM tb_event WHERE eventnr = ?' );
    if ( !$stmt ) {
        die( 'Prepare failed: ' . mysqli_error( $conn ) );
    }

    mysqli_stmt_bind_param( $stmt, 's', $event_id );
    $result = mysqli_stmt_execute( $stmt );
    mysqli_stmt_close( $stmt );

    return $result;
}

# Add Convert Page

function addConvert( $conn, $email, $fullname, $street, $zip, $city, $country, $cellphone, $telephone, $instagram, $facebook, $website, $usernr )
 {
    $query = "INSERT INTO tb_users (email, password, rcode, active, admin) VALUES ('{$email}', '', '', '0', '0')";
    mysqli_query( $conn, $query );
    $insertId = $conn->insert_id;
    $query = "INSERT INTO tb_members (type, fullname, organization, street, zip, city, country, cellphone, telephone, instagram, facebook, website) VALUES ('', '{$fullname}', '', '{$street}', '{$zip}', '{$city}', '{$country}', '{$cellphone}', '{$telephone}', '{$instagram}', '{$facebook}', '{$website}')";
    mysqli_query( $conn, $query );

    // if ( $email == '' ) {
    $currentDateTime = date( 'Y-m-d H:i:s' );

    $query = "INSERT INTO tb_connection (usernr1, usernr2, cdate, status) VALUES ('$usernr', '$insertId', '{$currentDateTime}', 'S')";
    return mysqli_query( $conn, $query );
    // }
}

function select_members( $conn, $s_type, $s_fullname, $s_organization, $s_zip, $s_city, $s_country, $admin )
 {
    if ( $admin ) {
        $query = "SELECT * FROM tb_users JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.active = '1' AND tb_members.fullname LIKE '%{$s_fullname}%' AND tb_members.zip LIKE '%{$s_zip}%' AND tb_members.type LIKE '%{$s_type}%' AND tb_members.organization LIKE '%{$s_organization}%' AND tb_members.city LIKE '%{$s_city}%' AND tb_members.country LIKE '%{$s_country}%'";
        return mysqli_query( $conn, $query );
    } else {
        $query = "SELECT * FROM tb_users JOIN tb_members ON tb_users.usernr = tb_members.usernr WHERE tb_users.active = '1' AND tb_members.fullname LIKE '%{$s_fullname}%' AND tb_members.zip LIKE '%{$s_zip}%' AND tb_members.type LIKE '%{$s_type}%' AND tb_members.organization LIKE '%{$s_organization}%' AND tb_members.city LIKE '%{$s_city}%' AND tb_members.country LIKE '%{$s_country}%' AND tb_members.active = 1";
        return mysqli_query( $conn, $query );
    }
}

function select_membersToMe( $conn, $s_type, $s_fullname, $s_organization, $s_zip, $s_city, $s_country ) {
    $query = "SELECT *
        FROM tb_connection
        JOIN tb_users ON tb_connection.usernr1 = tb_users.usernr OR tb_connection.usernr2 = tb_users.usernr
        JOIN tb_members ON tb_users.usernr = tb_members.usernr
        WHERE tb_users.active = '1'
        AND tb_members.fullname LIKE '%{$s_fullname}%'
        AND tb_members.zip LIKE '%{$s_zip}%'
        AND tb_members.type LIKE '%{$s_type}%'
        AND tb_members.organization LIKE '%{$s_organization}%'
        AND tb_members.city LIKE '%{$s_city}%'
        AND tb_members.country LIKE '%{$s_country}%'
        AND tb_members.active = 1";
    return mysqli_query( $conn, $query );
}

function select_newBorn( $conn, $usernr, $newBornnr )
 {
    $query = "SELECT * FROM tb_connection WHERE usernr1={$usernr} AND usernr2={$newBornnr} AND status='1'";

    return mysqli_query( $conn, $query );
}

function select_events( $conn, $sname, $sorg, $szip, $scity, $scountry, $sstartDate, $sendDate )
 {
    $query = "SELECT * FROM tb_event CROSS JOIN tb_members ON tb_event.usernr = tb_members.usernr CROSS JOIN tb_users ON tb_event.usernr = tb_users.usernr WHERE tb_users.active = '1' AND tb_event.name LIKE '%{$sname}%' AND tb_members.organization LIKE '%{$sorg}%' AND tb_event.zip LIKE '%{$szip}%' AND tb_event.city LIKE '%{$scity}%' AND tb_event.country LIKE '%{$scountry}%'";
    if ( $sstartDate && $sendDate ) {
        $query .= "AND tb_event.begindate = '{$sstartDate}' AND tb_event.enddate = '{$sendDate}'";
    }
    return mysqli_query( $conn, $query );
}

function select_policyByLang( $conn, $lang )
 {
    $query = "SELECT * FROM tb_default_lang WHERE langu='$lang'";
    return mysqli_query( $conn, $query );
}

function select_types( $conn )
 {
    $query = 'SELECT * FROM tb_types';
    return mysqli_query( $conn, $query );
}

function select_types_eng( $conn )
 {
    $query = "SELECT * FROM tb_types WHERE langu='english'";
    return mysqli_query( $conn, $query );
}

function select_event_detail( $conn, $event_id )
 {
    $query = "SELECT * FROM tb_members CROSS JOIN tb_event ON tb_event.usernr = tb_members.usernr CROSS JOIN tb_users ON tb_event.usernr = tb_users.usernr WHERE tb_event.eventnr = '{$event_id}'";
    return mysqli_query( $conn, $query );
}

function select_meFromEvent( $conn, $event_id, $usernr )
 {
    $query = "SELECT * FROM tb_event_att WHERE tb_event_att.usernr='{$usernr}' AND tb_event_att.eventnr='$event_id'";
    return mysqli_query( $conn, $query );
}

function attend_meToEvent( $conn, $usernr, $eventnr )
 {
    $cdate = date( 'Y-m-d H:i:s' );
    $query = "INSERT INTO tb_event_att (eventnr, usernr, cdate) VALUES ('{$eventnr}', '{$usernr}', '{$cdate}')";
    return mysqli_query( $conn, $query );
}

function delete_meFromEvent( $conn, $usernr, $eventnr )
 {
    $query = "DELETE FROM tb_event_att WHERE tb_event_att.eventnr='{$eventnr}' AND tb_event_att.usernr='{$usernr}'";
    return mysqli_query( $conn, $query );
}

function select_eventMembers( $conn, $event_id )
 {
    $query = "SELECT * FROM tb_event_att CROSS JOIN tb_users ON tb_event_att.usernr = tb_users.usernr CROSS JOIN tb_members ON tb_event_att.usernr = tb_members.usernr WHERE tb_event_att.eventnr='{$event_id}'";
    return mysqli_query( $conn, $query );
}

function update_activeMember( $conn, $usernr, $val )
 {
    $query = "UPDATE tb_users SET tb_users.active='{$val}' WHERE tb_users.usernr='{$usernr}'";
    return mysqli_query( $conn, $query );
}

function select_connectMembers( $conn, $usernr1, $usernr2 )
 {
    $query = "SELECT * FROM tb_connection WHERE tb_connection.usernr2='{$usernr2}' AND tb_connection.usernr1='{$usernr1}'";
    return mysqli_query( $conn, $query );
}

function insert_connectMember( $conn, $usernr1, $usernr2, $cdate, $status )
 {
    $currentDateTime = date( 'Y-m-d H:i:s' );
    $query = "INSERT INTO tb_connection (usernr1, usernr2, cdate, status) VALUES ('{$usernr1}', '{$usernr2}', '{$currentDateTime}', '{$status}')";
    return mysqli_query( $conn, $query );
}

function update_connectMembers( $conn, $usernr1, $usernr2, $cdate, $status )
 {
    $currentDateTime = date( 'Y-m-d H:i:s' );
    $query = "UPDATE tb_connection SET tb_connection.cdate='{$currentDateTime}', tb_connection.status='{$status}' WHERE tb_connection.usernr1='{$usernr1}' AND tb_connection.usernr2='{$usernr2}'";
    return mysqli_query( $conn, $query );
}

function delete_connectMembers( $conn, $usernr1, $usernr2 ) {
    $query = "DELETE FROM tb_connection WHERE tb_connection.usernr1='{$usernr1}' AND tb_connection.usernr2='{$usernr2}'";
    return mysqli_query( $conn, $query );
}

function select_evangel_lang( $conn, $evangelL )
 {
    $query = "SELECT * FROM tb_evangel WHERE langu='$evangelL'";
    return mysqli_query( $conn, $query );
}

function select_evangel_lang_sort( $conn )
 {
    $query = 'SELECT DISTINCT langu FROM tb_evangel ORDER BY langu';
    return mysqli_query( $conn, $query );
}

function create_connect( $conn, $usernr1, $usernr2 )
 {
    $currentDateTime = date( 'Y-m-d H:i:s' );
    $query = "INSERT INTO tb_connection (usernr1, usernr2, cdate, status) VALUES ('$usernr1', '$usernr2', '$currentDateTime', '1')";
    return mysqli_query( $conn, $query );
}

function update_connect( $conn, $usernr1, $usernr2 )
 {
    $query = "UPDATE tb_connection SET tb_connection.status = '1' WHERE tb_connection.usernr2='{$usernr2}' AND tb_connection.usernr1='{$usernr1}'";
    return mysqli_query( $conn, $query );
}

function delete_connect( $conn, $usernr1, $usernr2 )
 {
    $query = "UPDATE tb_connection SET tb_connection.status = '2' WHERE tb_connection.usernr2='{$usernr2}' AND tb_connection.usernr1='{$usernr1}'";
    return mysqli_query( $conn, $query );
}

// Add Convert

function logic_AddConvert( $conn, $email, $whatsapp, $usernr )
 {
    if ( $email ) {
        // send email
    } else {
        $code = '';
        if ( $whatsapp && $code ) {
            // send email
        } else {
            // tb_connection = 'S'
        }
    }

    // search church by zip @tb_connection = 0
}

// Remove my data from all database

function remove_me( $conn, $usernr ) {
    // Remove from tb_users
    $query = "DELETE FROM tb_users WHERE usernr = '{$usernr}'";
    mysqli_query( $conn, $query );

    // Remove from tb_members
    $query = "DELETE FROM tb_members WHERE usernr = '{$usernr}'";
    mysqli_query( $conn, $query );
}

// Whatsapp API Integration

// <?php

// require_once 'vendor/autoload.php';
// Include Twilio PHP SDK

// // Your Twilio Account SID and Auth Token
// $sid = 'your_twilio_account_sid';
// $token = 'your_twilio_auth_token';

// // Initialize Twilio client
// $client = new Twilio\Rest\Client( $sid, $token );

// // Send a message
// $message = $client->messages->create(
//     'whatsapp:+14155238886', // WhatsApp number to send message to
//     [
//         'from' => 'whatsapp:+14155238886', // Your WhatsApp number assigned by Twilio
//         'body' => 'Hello, this is a test message from Twilio!'
// ]
// );

// // Print the message SID
// echo 'Message SID: ' . $message->sid;
