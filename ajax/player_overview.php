<?php
	if(isset($_GET['player'])){
		$intPlayerID = $_GET['player'];
	} else {
		$intPlayerID = 2938;
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT FirstName, LastName, Position, College, Height_Feet, Height_Inches, Weight, DOB, date_format(DOB,'%c/%e/%Y') AS Birthdate, Hometown, Citizenship, Bio, Visible ";
	$sql .= "FROM tbl_players ";
	$sql .= "WHERE ID = ".$intPlayerID;

	$player = mysqli_query($connection, $sql) or die(mysqli_error($connection));

	while($row = @mysqli_fetch_array($player,MYSQLI_ASSOC)) {
		$strDOB = new DateTime($row['DOB']);
		$datToday = new DateTime();
		$intPlayerAge = $strDOB->diff($datToday);
		$strBirthdate = $row['Birthdate'];
		$strHometown = $row['Hometown'];
		$strCollege = $row['College'];
		$strHeight = $row['Height_Feet'].'\' '.$row['Height_Inches'].'"';
		$intWeight = $row['Weight'];
		$strCitizenship = $row['Citizenship'];
		$textBio = $row['Bio'];
	}

	$sql = "SELECT URL, Title FROM tbl_url WHERE PlayerID = ".$intPlayerID." ORDER BY Title ASC";
	$url = mysqli_query($connection, $sql) or die(mysqli_error($connection));

?>
	<dl>
		<dt>Citizenship</dt>
		<dd itemprop="nationality"><?php print $strCitizenship; ?></dd>
		
		<dt>Born</dt>
		<dd itemprop="birthDate"><a href="/date/<?php print date('n',strtotime($strBirthdate)).'/'.date('j',strtotime($strBirthdate)); ?>">
			<?php print $strBirthdate; ?>
		</a> (<?php print $intPlayerAge->y; ?> years old)</dd>

<?php
		if($strHometown){
			print '<dt>Hometown</dt><dd>'.$strHometown.'</dd>';
		}
		if($strCollege){
			print '<dt>College</dt><dd>'.$strCollege.'</dd>';
		}
		if($row['Height_Feet']>0) {
			print '<dt>Height</dt><dd>'.$strHeight.'</dd>';
		}
		if($intWeight) {
			print '<dt>Weight</dt><dd>'.$intWeight.'</dd>';
		}
		if(mysqli_num_rows($url)){
			print '<dt>Related Links</dt>';
			while($row = @mysqli_fetch_array($url,MYSQLI_ASSOC)) {
				print '<dd><a href="'.$row['URL'].'">'.$row['Title'].'</a></dd>';
			}
		}
?>
	</dl>
	<div class="bio clearboth"><?php print $textBio; ?></div>
<?php
	include_once("../includes/block_conn_close.php");
?>