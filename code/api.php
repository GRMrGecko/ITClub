<?
//
// api.php
// IT Club
//
// Copyright (c) 2015, Mr. Gecko's Media (James Coleman)
// All rights reserved.
//
// The main API for managing different sections of the site.
//

if ($_REQUEST['authentication']==$_MGM['apiAuthentication'] && $_MGM['path'][1]=="email") {
	$results = databaseQuery("SELECT * FROM members WHERE preferredMethod='Email' OR preferredMethod='Both'");
	$members = array();
	while ($result = databaseFetchAssoc($results)) {
		array_push($members, $result);
	}
	header("Content-Type: application/json");
	echo json_encode($members);
}
if ($_REQUEST['authentication']==$_MGM['apiAuthentication'] && $_MGM['path'][1]=="text") {
	$results = databaseQuery("SELECT * FROM members WHERE preferredMethod='Text' OR preferredMethod='Both'");
	$members = array();
	while ($result = databaseFetchAssoc($results)) {
		array_push($members, $result);
	}
	header("Content-Type: application/json");
	echo json_encode($members);
}

if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][1]=="settings") {
	if ($_MGM['path'][2]=="save") {
		$email = (isset($_REQUEST['email']) ? trim($_REQUEST['email']) : "");
		$replyToEmail = (isset($_REQUEST['replyToEmail']) ? trim($_REQUEST['replyToEmail']) : "");
		
		setSetting("email", $email);
		setSetting("replyToEmail", $replyToEmail);
	}
	exit();
}

if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][1]=="sidebar") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT * FROM `sidebar` ORDER BY `order`");
		while ($result = databaseFetchAssoc($results)) {
			?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="title"><?=htmlspecialchars($result['title'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="url"><?=htmlspecialchars($result['url'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="order"><?=htmlspecialchars($result['order'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td></tr><?
		}
	}
	if ($_MGM['path'][2]=="update") {
		$id = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
		$title = (isset($_REQUEST['title']) ? trim($_REQUEST['title']) : "");
		$url = (isset($_REQUEST['url']) ? trim($_REQUEST['url']) : "");
		$order = (isset($_REQUEST['order']) ? trim($_REQUEST['order']) : "");
		$results = databaseQuery("SELECT * FROM `sidebar` WHERE `id`=%s", $id);
		$result = databaseFetchAssoc($results);
		if ($result!=NULL) {
			databaseQuery("UPDATE `sidebar` SET `title`=%s,`url`=%s,`order`=%s WHERE `id`=%s", $title, $url, $order, $id);
		}
	}
	if ($_MGM['path'][2]=="delete") {
		$id = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
		$results = databaseQuery("SELECT * FROM `sidebar` WHERE `id`=%s", $id);
		$result = databaseFetchAssoc($results);
		if ($result!=NULL) {
			databaseQuery("DELETE FROM `sidebar` WHERE `id`=%s", $id);
		}
	}
	if ($_MGM['path'][2]=="add") {
		$title = (isset($_REQUEST['title']) ? trim($_REQUEST['title']) : "");
		$url = (isset($_REQUEST['url']) ? trim($_REQUEST['url']) : "");
		$order = (isset($_REQUEST['order']) ? trim($_REQUEST['order']) : "");
		if (!empty($title) && !empty($url)) {
			databaseQuery("INSERT INTO `sidebar` (`title`, `url`, `order`) VALUES (%s,%s,%s)", $title, $url, $order);
		}
	}
	exit();
}

if (isset($_MGM['user']) && $_MGM['user']['level']==1 && $_MGM['path'][1]=="users") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT * FROM users");
		while ($result = databaseFetchAssoc($results)) {
			$level = "Normal";
			if ($result['level']==0)
				$level = "Disabled";
			if ($result['level']==1)
				$level = "Administrator";
			?><tr><td class="id"><?=htmlspecialchars($result['docid'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="email"><?=htmlspecialchars($result['email'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="level" value="<?=htmlspecialchars($result['level'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?>"><?=$level?></td></tr><?
		}
	}
	if ($_MGM['path'][2]=="update") {
		$id = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
		$email = (isset($_REQUEST['email']) ? trim($_REQUEST['email']) : "");
		$password = (isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "");
		$level = (isset($_REQUEST['level']) ? trim($_REQUEST['level']) : "");
		$results = databaseQuery("SELECT * FROM users WHERE docid=%s", $id);
		$result = databaseFetchAssoc($results);
		if ($result!=NULL) {
			if (empty($email))
				$email = $result['email'];
			$epassword = $result['password'];
			if (!empty($password)) {
				$salt = substr(sha1(rand()),0,12);
				$epassword = $salt.hashPassword($password,hex2bin($salt));
			}
			if ($level=="")
				$level = $result['level'];
			databaseQuery("UPDATE users SET email=%s,password=%s,level=%s WHERE docid=%s", $email, $epassword, $level, $id);
		}
	}
	if ($_MGM['path'][2]=="create") {
		$email = (isset($_REQUEST['email']) ? trim($_REQUEST['email']) : "");
		$password = (isset($_REQUEST['password']) ? trim($_REQUEST['password']) : "");
		$level = (isset($_REQUEST['level']) ? trim($_REQUEST['level']) : "");
		if (!empty($email) && !empty($level)) {
			$salt = substr(sha1(rand()),0,12);
			$epassword = $salt.hashPassword($password,hex2bin($salt));
			databaseQuery("INSERT INTO users (email, password, time, level) VALUES (%s,%s,%s,%s)", $email, $epassword, $_MGM['time'], $level);
		}
	}
	exit();
}
if (isset($_MGM['user']) && $_MGM['path'][1]=="members") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT * FROM members");
		while ($result = databaseFetchAssoc($results)) {
			?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="name"><?=htmlspecialchars($result['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="position"><?=htmlspecialchars($result['position'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="phone"><?=htmlspecialchars($result['phone'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="email"><?=htmlspecialchars($result['email'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="preferredMethod"><?=htmlspecialchars($result['preferredMethod'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td></tr><?
		}
	}
	if ($_MGM['path'][2]=="download") {
		function csvQuote($text) {
			return "\"".str_replace("\"", "\"\"", $text)."\"";
		}
		echo "#,Name,Position,Phone,Email,Preferred Method\n";
		
		$results = databaseQuery("SELECT * FROM data");
		while ($result = databaseFetchAssoc($results)) {
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Description: File Transfer");
			header("Content-Disposition: inline; filename=\"".date("Y m d")." Data.csv\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Type: application/csv");
			
			$shirts = "";
			$selectedShirts = json_decode($result['shirts']);
			for ($i=0; $i<count($selectedShirts); $i++) {
				if ($i!=0)
					$shirts .= ", ";
				$shirts .= $allShirts[$selectedShirts[$i]];
			}
			echo $result['id'].",".csvQuote($result['name']).",".csvQuote($result['position']).",".csvQuote($result['phone']).",".csvQuote($result['email']).",".csvQuote($result['preferredMethod']);
		}
	}
	
	if ($_MGM['path'][2]=="upload") {
		$uploadPath = "/tmp/itmembersupload.csv";
		$input = fopen("php://input", "r");
		if (file_exists($uploadPath))
			unlink($uploadPath);
		$output = fopen($uploadPath, "w");
	
		while ($data = fread($input, 1024))
			fwrite($output, $data);
	
		fclose($output);
		fclose($input);
	
		$handle = fopen($uploadPath, "r");
		$headers = array();
		$readHeaders = false;
		$entries = array();
		while (($data = fgetcsv($handle, 1000, ",")) !== false) {
			if (count($data)==5) {
				if (!$readHeaders) {
					for ($i=0; $i<count($data); $i++) {
						if (preg_match("/Name/i", $data[$i])) {
							$headers['name'] = $i;
						} else if (preg_match("/Position/i", $data[$i])) {
							$headers['position'] = $i;
						} else if (preg_match("/Phone/i", $data[$i])) {
							$headers['phone'] = $i;
						} else if (preg_match("/Email/i", $data[$i])) {
							$headers['email'] = $i;
						} else if (preg_match("/Preferred/i", $data[$i])) {
							$headers['preferredMethod'] = $i;
						} else {
							echo "Unknown column header: ".$data[$i]."\n";
							unlink($uploadPath);
							exit();
						}
					}
					$readHeaders = true;
				} else {
					if (count($headers)!=5) {
						echo "Bad header count: ".count($headers)."\n";
						unlink($uploadPath);
						exit();
					}
					$entry = array();
					$entry['name'] = $data[$headers['name']];
					$entry['position'] = $data[$headers['position']];
					$entry['phone'] = preg_replace("/[^0-9]/", "", $data[$headers['phone']]);
					$entry['email'] = $data[$headers['email']];
					$entry['preferredMethod'] = $data[$headers['preferredMethod']];
					array_push($entries, $entry);
				}
			} else {
				echo "Bad column count: ".count($data)."\n";
				unlink($uploadPath);
				exit();
			}
		}
		fclose($handle);
		
		databaseQuery("DELETE FROM members");
		databaseQuery("ALTER TABLE members AUTO_INCREMENT=1");
		for ($i=0; $i<count($entries); $i++) {
			$entry = $entries[$i];
			databaseQuery("INSERT INTO members (name,position,phone,email,preferredMethod) VALUES(%s,%s,%s,%s,%s)", $entry['name'], $entry['position'], $entry['phone'], $entry['email'], $entry['preferredMethod']);
		}
		
		unlink($uploadPath);
		echo "uploaded";
	}
	exit();
}
if (isset($_MGM['user']) && $_MGM['path'][1]=="meetings") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT * FROM meetings");
		while ($result = databaseFetchAssoc($results)) {
			$rsvps = databaseQuery("SELECT SUM(IF(choice=0,1,0)) AS going,SUM(IF(choice=1,1,0)) AS maybe,SUM(1) AS responses FROM rsvp WHERE meeting=%s", $result['id']);
			$rsvp = databaseFetchAssoc($rsvps);
			?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="date"><?=date("l M j, h:i A", $result['date'])?></td><td class="location"><?=htmlspecialchars($result['location'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="rsvp">G <?=$rsvp['going']?> M <?=$rsvp['maybe']?> R <?=$rsvp['responses']?></td><td class="options"><button class="btn btn-info edit">Edit</button><button class="btn btn-success view">View RSVP</button><button class="btn btn-primary rsvp">RSVP</button></td></tr><?
		}
	}
	
	if ($_MGM['path'][2]=="add") {
		$date = (isset($_REQUEST['date']) ? trim($_REQUEST['date']) : "");
		$location = (isset($_REQUEST['location']) ? trim($_REQUEST['location']) : "");
		
		$time = strtotime($date);
		if ($time==0) {
			echo "Bad date.";
			exit();
		}
		
		if (!empty($location)) {
			databaseQuery("INSERT INTO meetings (date, location) VALUES (%s,%s)", $time, $location);
			echo "Successfully Added.";
		} else {
			echo "Missing Data.";
		}
	}
	
	if ($_MGM['path'][2]=="save") {
		$id = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
		$date = (isset($_REQUEST['date']) ? trim($_REQUEST['date']) : "");
		$location = (isset($_REQUEST['location']) ? trim($_REQUEST['location']) : "");
		
		$time = strtotime($date);
		if ($time==0) {
			echo "Bad date.";
			exit();
		}
		
		if (!empty($id) && intVal($id)!=0 && !empty($location)) {
			databaseQuery("UPDATE meetings SET date=%s,location=%s WHERE id=%s", $time, $location, $id);
			echo "Successfully Saved.";
		} else {
			echo "Missing Data.";
		}
	}
	
	if (!empty($_MGM['path'][2]) && intVal($_MGM['path'][2])!=0) {
		$id = intVal($_MGM['path'][2]);
		if ($_MGM['path'][3]=="list") {
			$results = databaseQuery("SELECT * FROM rsvp WHERE meeting=%s", $id);
			while ($result = databaseFetchAssoc($results)) {
				?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="date"><?=date("m/d/y h:i:s A", $result['date'])?></td><td class="name"><?=htmlspecialchars($result['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="contact"><?=htmlspecialchars($result['contact'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="rsvp"><?=($result['choice']==0 ? "Going" : ($result['choice']==1 ? "Maybe" : "Not Attending"))?></td><td class="options"><button class="btn btn-success going">Going</button><button class="btn btn-info maybe">Maybe</button><button class="btn btn-danger not_attending">Not Attending</button></td></tr><?
			}
		}
		
		if ($_MGM['path'][3]=="going") {
			$rsvpID = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
			
			if (!empty($rsvpID) && intVal($id)!=0) {
				databaseQuery("UPDATE rsvp SET choice=0 WHERE id=%s", $rsvpID);
			} else {
				echo "Missing Data.";
			}
		}
		if ($_MGM['path'][3]=="maybe") {
			$rsvpID = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
			
			if (!empty($rsvpID) && intVal($id)!=0) {
				databaseQuery("UPDATE rsvp SET choice=1 WHERE id=%s", $rsvpID);
			} else {
				echo "Missing Data.";
			}
		}
		if ($_MGM['path'][3]=="not_attending") {
			$rsvpID = (isset($_REQUEST['id']) ? trim($_REQUEST['id']) : "");
			
			if (!empty($rsvpID) && intVal($rsvpID)!=0) {
				databaseQuery("UPDATE rsvp SET choice=3 WHERE id=%s", $rsvpID);
			} else {
				echo "Missing Data.";
			}
		}
	}
}
if (isset($_MGM['user']) && $_MGM['path'][1]=="announcements") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT *,(SELECT email FROM users WHERE user=docid) AS email FROM announcements");
		while ($result = databaseFetchAssoc($results)) {
			?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="email"><?=htmlspecialchars($result['email'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="subject"><?=htmlspecialchars($result['subject'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="message"><?=str_replace("\n","<br />",htmlspecialchars($result['message'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true))?></td><td class="sms"><?=htmlspecialchars($result['sms'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="date"><?=date("m/d/y h:i:s A", $result['date'])?></td></tr><?
		}
	}
	
	if ($_MGM['path'][2]=="send") {
		$subject = (isset($_REQUEST['subject']) ? trim($_REQUEST['subject']) : "");
		$message = (isset($_REQUEST['message']) ? trim($_REQUEST['message']) : "");
		$smsmessage = (isset($_REQUEST['smsmessage']) ? trim($_REQUEST['smsmessage']) : "");
		
		if (strlen($smsmessage)>160) {
			echo "SMS Message it too long.";
			exit();
		}
		
		if ((!empty($subject) && !empty($message)) || !empty($smsmessage)) {
			databaseQuery("INSERT INTO announcements (user, subject, message, sms, date) VALUES (%s,%s,%s,%s,%s)", $_MGM['user']['docid'], $subject, $message, $smsmessage, $_MGM['time']);
			
			$email = getSetting("email");
			$replyToEmail = getSetting("replyToEmail");
			
			if (!empty($subject) && !empty($message)) {
				$headers = "From: ".$email."\r\n";
				$headers .= "Reply-to: ".$replyToEmail."\r\n";
				
				$results = databaseQuery("SELECT * FROM members WHERE preferredMethod='Email' OR preferredMethod='Both'");
				$oneSuccessful = false;
				while ($result = databaseFetchAssoc($results)) {
					$address = $result['email'];
					if (mail($address, $subject, $message, $headers)) {
						$oneSuccessful = true;
					}
				}
			
				if ($oneSuccessful) {
					echo "Successfully Sent.";
				} else {
					echo "Failure sending email.";
				}
			}
			
			if (!empty($smsmessage)) {
				echo "SMS not implemented.";
			}
		} else {
			echo "Missing Data.";
		}
	}
}

if ($_MGM['path'][1]=="rsvp") {
	if ($_MGM['path'][2]=="list") {
		$results = databaseQuery("SELECT * FROM meetings WHERE date>=%d", $_MGM['time']);
		while ($result = databaseFetchAssoc($results)) {
			?><tr><td class="id"><?=htmlspecialchars($result['id'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td><td class="date"><?=date("l M j, h:i A", $result['date'])?></td><td class="location"><?=htmlspecialchars($result['location'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true)?></td></tr><?
		}
	}
	
	if (!empty($_MGM['path'][2]) && intVal($_MGM['path'][2])!=0) {
		$id = intVal($_MGM['path'][2]);
		
		if ($_MGM['path'][3]=="submit") {
			$name = (isset($_REQUEST['name']) ? trim($_REQUEST['name']) : "");
			$contact = (isset($_REQUEST['contact']) ? trim($_REQUEST['contact']) : "");
			$choice = (isset($_REQUEST['choice']) ? trim($_REQUEST['choice']) : "");
			
			if ((empty($choice) && $choice!=0) || empty($name)) {
				?><span style="color: #ff0000">Missing fields.</span><?
				exit();
			}
			
			if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
				$contact = preg_replace("/[^0-9]/", "", $contact);
				if (strlen($contact)==7) {
					$contact = "256".$contact;
				} else if (strlen($contact)!=10) {
					?><span style="color: #ff0000">Invalid contact info.</span><?
					exit();
				}
			}
			
			$rsvps = databaseQuery("SELECT * FROM rsvp WHERE meeting=%s AND contact=%s", $id, $contact);
			$rsvp = databaseFetchAssoc($rsvps);
			if ($rsvp!=NULL) {
				databaseQuery("UPDATE rsvp SET choice=%s WHERE meeting=%s AND contact=%s", $choice, $id, $contact);
				?><span style="color: #00ff00">Your RSVP was updated.</span><?
			} else {
				databaseQuery("INSERT INTO rsvp (meeting,name,contact,choice,date) VALUES (%s,%s,%s,%s,%s)", $id, $name, $contact, $choice, $_MGM['time']);
				?><span style="color: #00ff00">Your RSVP was submitted.</span><?
			}
		}
	}
}
exit();
?>