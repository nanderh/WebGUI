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
		<title>Raspberry Control - DHCP Server</title>
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
							<a>DHCP Server</a>
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
						include('phpincs.php');
						$output = $return = 0;
						exec('cat /etc/udhcpd.conf',$return);
						$conf = ParseConfig($return);
						$conf2 = ParseConfig2($return);
						$RangeStart = $conf[start];
						$leasetime = $conf2[option];
						$sselected = " selected";
						$RangeEnd = $conf[end];
						exec('pidof udhcpd | wc -l',$dnsmasq);
						if($dnsmasq[0] == 0) {
							$status = 'udhcpd not running';
						} else {
							$status = 'udhcpd is running';
						}
						
						echo '
						<form method="POST">
						<div id="box1">
							<h2>
								DHCP Server Information
							</h2>
							<p>
								Status : '.$status.'<br />
								Interface: '.$conf['interface'].'<br />
								Range Start: '.$RangeStart.'<br />
								Range End: '.$RangeEnd.'<br />
								Lease Time: '.$leasetime.'<br />
							</p>
						</div>
						<div id="box1">
							<h2>
								DHCP Server Controls
							</h2>
							<p>
								<input type="submit" value="Start UDHCPD" name="startudhcpd" />
								<input type="submit" value="Stop UDHCPD" name="stopudhcpd" />
								<input type="submit" value="Restart UDHCPD" name="restartudhcpd" />
							</p>
						</div>
						<br class="clear" />';
						if(isset($_POST['startudhcpd'])) {
							exec('sudo ifconfig wlan1 10.0.0.1');
							exec('sudo service udhcpd start',$return);
							echo "Attempting to start udhcpd";
						}

						if(isset($_POST['stopudhcpd'])) {
							exec('sudo service udhcpd stop',$return);
							echo "Stopping udhcpd";
						}
						
						if(isset($_POST['restartudhcpd'])) {
							exec('sudo service udhcpd stop',$return);
							exec('sudo ifconfig wlan1 10.0.0.1');
							exec('sudo service udhcpd start',$return);
							echo "Restarting udhcpd";
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
