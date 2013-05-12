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
		<title>Raspberry Control - Wireless Client</title>
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
							<a>Wireless Client</a>
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
						exec('ifconfig wlan0',$return);
						exec('iwconfig wlan0',$return);
						$strWlan0 = implode(" ",$return);
						$strWlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
						preg_match('/HWaddr ([0-9a-f:]+)/i',$strWlan0,$result);
						$strHWAddress = $result[1];
						preg_match('/inet addr:([0-9.]+)/i',$strWlan0,$result);
						$strIPAddress = $result[1];
						preg_match('/Mask:([0-9.]+)/i',$strWlan0,$result);
						$strNetMask = $result[1];
						preg_match('/RX packets:(\d+)/',$strWlan0,$result);
						$strRxPackets = $result[1];
						preg_match('/TX packets:(\d+)/',$strWlan0,$result);
						$strTxPackets = $result[1];
						preg_match('/RX Bytes:(\d+ \(\d+.\d+ MiB\))/i',$strWlan0,$result);
						$strRxBytes = $result[1];
						preg_match('/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strWlan0,$result);
						$strTxBytes = $result[1];
						preg_match('/ESSID:\"([a-zA-Z0-9\s]+)\"/i',$strWlan0,$result);
						$strSSID = str_replace('"','',$result[1]);
						preg_match('/Access Point: ([0-9a-f:]+)/i',$strWlan0,$result);
						$strBSSID = $result[1];
						preg_match('/Bit Rate=([0-9]+ Mb\/s)/i',$strWlan0,$result);
						$strBitrate = $result[1];
						preg_match('/Tx-Power=([0-9]+ dBm)/i',$strWlan0,$result);
						$strTxPower = $result[1];
						preg_match('/Link Quality=([0-9]+\/[0-9]+)/i',$strWlan0,$result);
						$strLinkQuality = $result[1];
						preg_match('/Signal Level=(-[0-9]+ dBm)/i',$strWlan0,$result);
						$strSignalLevel = $result[1];
						if(strpos($strWlan0, "UP") !== false && strpos($strWlan0, "RUNNING") !== false) {
							$strStatus = 'Interface is up';
						} else {
							$strStatus = 'Interface seems down';
						}
						echo '
							<form method="POST">
							<div id="box1">
							<h2>
								Interface Information
							</h2>
							<p>
								Interface Name : wlan0<br />
								Interface Status : ' . $strStatus . '<br />
								IP Address : ' . $strIPAddress . '<br />
								Subnet Mask : ' . $strNetMask . '<br />
								Mac Address : ' . $strHWAddress . '<br />
							</p>
						</div>
						<div id="box1">
							<h2>
								Interface Statistics
							</h2>
							<p>
								Received Packets : ' . $strRxPackets . '<br />
								Received Bytes : ' . $strRxBytes . '<br /><br />
								Transferred Packets : ' . $strTxPackets . '<br />
								Transferred Bytes : ' . $strTxBytes . '<br />
							</p>
						</div>
						<div id="box1">
							<h2>
								Wireless Information
							</h2>
							<p>
								Connected To : ' . $strSSID . '<br />
								AP Mac Address : ' . $strBSSID . '<br />
								Bitrate : ' . $strBitrate . '<br />
								Transmit Power : ' . $strTxPower .'<br />
								Link Quality : ' . $strLinkQuality . '<br />
								Signal Level : ' . $strSignalLevel . '<br />
							</p>
						</div>
						<div id="box1">
							<h2>
								WPA Supplicant info
							</h2>
							<p>
								<textarea name="review" cols=77 rows=10>';
								$wpasupplicant = @fopen("/etc/wpa_supplicant/wpa_supplicant.conf", 'r');
								if ($wpasupplicant) {
									echo fread($wpasupplicant, filesize("/etc/wpa_supplicant/wpa_supplicant.conf"));
								}
								echo '
								</textarea><br />
								---Add Network--- <br />
								SSID : <input type="text" name="ssid" value="" /> <br />
								Password : <input type="text" name="password" value="" /> <br />
								<input type="submit" value="Add Network" name="addnetwork" />
							</p>
						</div>
						<div id="box1">
							<h2>
								Interface Controls
							</h2>
							<p>
								<input type="submit" value="Start wlan0" name="startwlan0" />
								<input type="submit" value="Stop wlan0" name="stopwlan0" />
							</p>
						</div>
						<br class="clear" />';
						
						if(isset($_POST['stopwlan0'])) {
							exec('sudo ifdown wlan0',$return);
							echo 'Putting interface down';
						}
						if(isset($_POST['startwlan0'])) {
							exec('sudo ifup wlan0',$return);
							echo 'Putting interface up';
						}
						if(isset($_POST['addnetwork'])) {
							$command1 = 'sudo bash -c "wpa_passphrase %1$s %2$s >> /etc/wpa_supplicant/wpa_supplicant.conf"';
							$command2 = sprintf($command1, $_POST['ssid'], $_POST['password']);
							exec($command2,$return);
							echo 'Adding network';
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
