<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

	Design by Free CSS Templates
	http://www.freecsstemplates.org
	Released for free under a Creative Commons Attribution License

	Name       : Neutral Charisma
	Version    : 1.0
	Released   : 20130222

-->
<html>
	<head>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Raspberry Control - Access Point</title>
		<link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<div id="bg">
			<div id="outer">
				<div id="header">
					<div id="logo">
						<h1>
							<a>Access Point</a>
						</h1>
					</div>
					<div id="nav">
						<ul>
							<li class="first active">
								<a href="index.php">Home</a>
							</li>
							<li>
								<a href="client.php">Wireless Client</a>
							</li>
							<li>
								<a href="ap.php">Access Point</a>
							</li>
							<li>
								<a href="dhcp.php">DHCP Server</a>
							</li>
							<li>
								<a href="sslstrip.php">SSLstrip</a>
							</li>
							<li>
								<a href="dnsspoof.php">Dnsspoof</a>
							</li>
							<li>
								<a href="downloads.php">Downloads</a>
							</li>
							<li class="last">
								<a href="admin.php">Administration</a>
							</li>
						</ul>
						<br class="clear" />
					</div>
				</div>
				<div id="main">
					<div id="sidebar1">
						<h3>
							Status:
						</h3>
						
						<ul class="linkedList">
							<li class="first">
								<a href="client.php">Wireless Client: </a><br />
								<?php
								exec('ifconfig wlan0',$return);
								$strWlan0 = implode(" ",$return);
								$strWlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
								if(strpos($strWlan0, "UP") !== false && strpos($strWlan0, "RUNNING") !== false) {
									echo "Wireless client connected";
								} else {
									echo "Wireless client not connected";
								}
								?>
							</li>
							<li>
								<a href="ap.php">Access point: </a><br />
								<?php
								exec('pidof hostapd | wc -l',$hostapdstatus);
								if($hostapdstatus[0] == 0) {
									echo "Access point not running";
								} else {
									echo "Access point running";
								}
								?>
							</li>
							<li>
								<a href="dhcp.php">DHCP Server: </a><br />
								<?php
								exec('pidof udhcpd | wc -l',$dnsmasq);
								if($dnsmasq[0] == 0) {
									echo "DHCP Server not running";
								} else {
									echo "DHCP Server running";
								}
								?>
							</li>
							<li>
								<a href="sslstrip.php#arpspoof">Arpspoof: </a><br />
								<?php
								exec('pidof arpspoof | wc -l',$arpspoofok);
								if($arpspoofok[0] == 0) {
									echo "Arpspoof is not running";
								} else {
									echo "Arpspoof is running";
								}
								?>
							</li>
							<li>
								<a href="sslstrip.php#sslstrip">Sslstrip: </a><br />
								<?php 
								exec('pgrep -f sslstrip | wc -l',$sslstripok);
								if($sslstripok[0] == 1) {
									echo "Sslstrip is not running";
								} else {
									echo "Sslstrip is running";
								}
								?>
							</li>
							<li>
								<a href="sslstrip.php#cleaner">Cleaner: </a><br />
								<?php 
								exec('pgrep -f cleaner.py | wc -l',$cleanok);
								if($cleanok[0] == 1) {
									echo "Cleaner is not running";
								} else {
									echo "Cleaner is running";
								}
								?>
							</li>
							<li class="last">
								<a href="dnsspoof.php">Dnsspoof</a><br />
								Dnsspoof not running
							</li>
						</ul>
					</div>
					<div id="sidebar2">
						<h3>
							Info:
						</h3>
						<p>
							Some help info
						</p>
					</div>
					<div id="content">
						<?php
						$output = $return = 0;
						exec('cat /etc/hostapd/hostapd.conf',$return);
						exec('pidof hostapd | wc -l',$hostapdstatus);
						if($hostapdstatus[0] == 0) {
							$status = 'Access Point Not Running';
						} else {
							$status = 'Access Point Running';
						}
						
						$arrConfig = array();
						$arrChannel = array('a','b','g');
						$arrSecurity = array( 1 => 'WPA', 2 => 'WPA2',3=> 'WPA+WPA2');
						$arrEncType = array('TKIP' => 'TKIP', 'CCMP' => 'CCMP', 'TKIP CCMP' => 'TKIP+CCMP');
						
						foreach($return as $a) {
							if($a[0] != "#") {
								$arrLine = explode("=",$a);
								$arrConfig[$arrLine[0]]=$arrLine[1];
							}
						}
						
						echo '
						<form method="POST">
						<div id="box1">
							<h2>
								Access Point Information
							</h2>
							<p>
								Status: '.$status.'<br />
								Interface: '.$arrConfig['interface'].'<br />
								SSID: '.$arrConfig['ssid'].'<br />
								Wireless Mode: '.$arrConfig['hw_mode'].'<br />
								Channel: '.$arrConfig['channel'].'<br />
								Security Type: '.$arrConfig['wpa'].'<br />
								Encryption Type: '.$arrConfig['wpa_pairwise'].'<br />
								PSK : '.$arrConfig['wpa_passphrase'].'
							</p>
						</div>
						<div id="box1">
							<h2>
								Access Point Controls
							</h2>
							<p>
								Interface : <select name="interface">';
								exec("cat /proc/net/dev | tail -n -4 | awk -F :\  ' { print $1 } ' | tr -d ' '",$interfaces);
								foreach($interfaces as $int) {
									$select = '';
									if($int == $arrConfig['interface']) {
										$select = " selected";
									}
									echo '<option value="'.$int.'"'.$select.'>'.$int.'</option>';
								}
								echo'</select><br />
								SSID : <input type="text" name="ssid" value="'.$arrConfig['ssid'].'" /><br />
								Wireless Mode : <select name="hw_mode">';
								foreach($arrChannel as $Mode) {
									$select = '';
									if($arrConfig['hw_mode'] == $Mode) {
										$select = ' selected';
									}
									echo '<option value="'.$Mode.'"'.$select.'>'.$Mode.'</option>';
								}
								echo '</select><br />
								Channel : <select name="channel">';
								for($channel = 1; $channel < 14; $channel++) {
									$select = '';
									if($channel == $arrConfig['channel']) {
										$select = " selected";
									}
									echo '<option value="'.$channel.'"'.$select.'>'.$channel.'</option>';
								}
								echo '</select><br />
								Security type : <select name="wpa">';
								foreach($arrSecurity as $SecVal => $SecMode) {
									$select = '';
									if($SecVal == $arrConfig['wpa']) {
										$select = ' selected';
									}
									echo '<option value="'.$SecVal.'"'.$select.'>'.$SecMode.'</option>';
								}
								echo'</select><br />
								Encryption Type : <select name="wpa_pairwise">';
								foreach($arrEncType as $EncConf => $Enc) {
									$select = '';
									if($Enc == $arrConfig['wpa_pairwise']) {
										$select = ' selected';
									}
										echo '<option value="'.$EncConf.'"'.$select.'>'.$Enc.'</option>';
								}
								echo'</select><br />
								PSK : <input type="text" name="wpa_passphrase" value="'.$arrConfig['wpa_passphrase'].'" /> <br />
								<input type="submit" name="SaveHostAPDSettings" value="Save Hostapd settings" /> ';
								if($hostapdstatus[0] == 0) {
									echo '<input type="submit" name="StartHotspot" value="Start Hotspot" />';
								} else {
									echo '<input type="submit" name="StopHotspot" value="Stop hotspot" />';
								}
								echo '<input type="submit" name="RestartHotspot" value="Restart hotspot" />
							</p>
						</div>
						<br class="clear" />';
								if(isset($_POST['SaveHostAPDSettings'])) {
									$config = 'driver=nl80211
									ctrl_interface=/var/run/hostapd
									ctrl_interface_group=0
									beacon_int=100
									auth_algs=1
									wpa_key_mgmt=WPA-PSK
									';
									$config .= "interface=".$_POST['interface']."
									";
									$config .= "ssid=".$_POST['ssid']."
									";
									$config .= "hw_mode=".$_POST['hw_mode']."
									";
									$config .= "channel=".$_POST['channel']."
									";
									$config .= "wpa=".$_POST['wpa']."
									";
									$config .='wpa_passphrase='.$_POST['wpa_passphrase'].'
									';
									$config .="wpa_pairwise=".$_POST['wpa_pairwise']."
									";
									$config .="country_code=".$_POST['country_code'];
									exec("echo '$config' > /tmp/hostapddata",$return);
									system("sudo cp /tmp/hostapddata /etc/hostapd/hostapd.conf",$return);
									if($return == 0) {
										echo "Wifi Hotspot settings saved";
									} else {
										echo "Wifi Hotspot settings failed to be saved";
									}
								
								} elseif(isset($_POST['StartHotspot'])) {
									echo "Attempting to start hotspot";
									exec('sudo hostapd -B /etc/hostapd/hostapd.conf',$return);
								} elseif(isset($_POST['StopHotspot'])) {
									echo "Attempting to stop hotspot";
									exec('pidof hostapd',$pid);
									$command1 = 'sudo kill %d';
									exec(sprintf($command1, $pid[0]));
								}
								if(isset($_POST['RestartHotspot'])) {
									exec('pidof hostapd',$pid);
									$command1 = sprintf('sudo kill %d', $pid[0]);
									exec($command1);
									sleep(5);
									exec('sudo hostapd -B /etc/hostapd/hostapd.conf');
								}
						?>
					</div>
					<br class="clear" />
				</div>
			</div>
			<div id="copyright">
				&copy; Your Site Name | Design by <a href="http://www.freecsstemplates.org/">FCT</a>
			</div>
		</div>
	</body>
</html>
