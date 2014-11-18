																																																																																																			<?php
if (!defined("WHMCS"))
{
	exit("This file cannot be accessed directly");
}

function COCCAReg_config() {

	$configarray = array("name" => "COCCA Registrar Manager", "description" => "Makes entries to several COCCA EPP Registrar Configurations.","author" => "Mygeek Consulting", "language" => "english");
	return $configarray;
}

function COCCAReg_activate() {

	$query = "CREATE TABLE `mod_COCCARegtlds` (`id` INT( 1 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`tld` TEXT NOT NULL,`hostname` TEXT NULL,`port` TEXT NULL,`username` TEXT NULL,`password` TEXT NULL,`protocol` TEXT NULL,`debug` TEXT NULL,`whoisserver` TEXT NULL )";
	$result = mysql_query($query);	
	
	return array("status" => "success", "description" => "This is an demo module only. In a real module you might instruct a user how to get started with it here...");
}

function COCCAReg_deactivate() {

	$query = "DROP TABLE `mod_COCCARegtlds`";
	$result = mysql_query($query);	
	return array("status" => "success", "description" => "If successful, you can return a message to show the user here");
}

function COCCAReg_upgrade($vars) {
	return;
}

function COCCAReg_output($vars) {

	$modulelink = $vars["modulelink"];
	$version = $vars["version"];
	$option1 = $vars["option1"];
	$option2 = $vars["option2"];
	$option3 = $vars["option3"];
	$option4 = $vars["option4"];
	$option5 = $vars["option5"];
	$LANG = $vars["_lang"];
	

	   if ( !isset ( $_GET['subtab'] ) )
    {
        $_GET['subtab'] = "active";
    }

    // Tab styling
    $activestyle = " style=\"background-color: #FFF !important;border-bottom:solid 1px white !important;\"";
    $out_inactive = false;
    $out_active = false;
    $out_all = false;
    switch ($_GET['subtab']) {
        case "inactive":
            $active_in = $activestyle;
            $out_inactive = true;
           echo "
        <div id=\"clienttabs\">
        	<ul>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=active\"{$active_ac}>Registries Display</a></li>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=inactive\"{$active_in}>Add New</a></li>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=all\"{$active_al}>View Logs</a></li>
            </ul>
        </div>
        <div id=\"tab0box\" class=\"tabbox\">
            <div id=\"tab_content\" style=\"text-align:left;\">
             <form method=\"post\" action=\"" . $modulelink . "&action=tlds\">\r
			<div class=\"tablebg\">\r
			<table class=\"datatable\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\r
			<tr><th>TLD</th><th>Hostname</th><th>Port</th><th>Protocol</th><th>Username</th><th>Password</th><th>Timeout</th><th>Whois Server</th><th>Debug</th></tr>";
			echo "<tr><td><input type=\"text\" name=\"extension\" size=\"10\">" . "</td>. ";
			echo "<td><input type=\"text\" name=\"hostname\" size=\"25\" /></td><td><input type=\"text\" name=\"port\" size=\"10\" /></td><td>\r
					<select name=\"protocol\">\r
					<option value=\"tcp\"";
			if ($protocol == "tcp")
			{
				echo " selected=\"selected\"";
			}
			echo ">TCP</option>\r
					<option value=\"ssl\"";
			if ($protocol == "ssl")
			{
				echo " selected=\"selected\"";
			}
			echo ">SSL</option>\r
					<option value=\"tls\"";
			if ($protocol == "tls")
			{
				echo " selected=\"selected\"";
			}
			echo ">TLS</option>\r
					<option value=\"http\"";
			if ($protocol == "http")
			{
				echo " selected=\"selected\"";
			}
			echo ">HTTP</option>\r
					<option value=\"https\"";
			if ($protocol == "https")
			{
				echo " selected=\"selected\"";
			}
			echo ">HTTPS</option>\r
					</select></td><td><input type=\"text\" name=\"username\" size=\"15\" /></td><td><input type=\"password\" name=\"password\" size=\"15\" /></td><td><input type=\"text\" name=\"timeout\" size=\"10\"/></td><td><input type=\"text\" name=\"whoisserver\" size=\"20\" /></td><td><input type=\"checkbox\" name=\"debug\" ";
			if ($debug == "on")
			{
				echo "checked=\"checked\" ";
			}
			echo "/></td></tr>";
			//continue;
		
		echo "</table>\r
</div>\r
\r
<p align=\"center\"><input type=\"submit\" name=\"tldsubmit\" value=\"Submit\" class=\"button\" /></p>\r
\r
</form>\r
\r
";
//require_once (dirname(__file__) . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . 'form.php'); 
		echo "</div>\r
		</div></div>\r
			<div class=\"clear\"></div>";
	
            
            break;
        case "all":
            $active_al = $activestyle;
            $out_all = true;
            break;
        case "active":
        default:
            $active_ac = $activestyle;
            $out_active = true;
             $i=0;
	  $result = select_query("mod_COCCARegtlds", "");
	  if (mysql_num_rows($result) == 0){
	  
        $out = "<tr><td colspan=\"4\">No TLDS Added Yet</td></tr>";
    } else {
	  while ($data = mysql_fetch_array($result))
		{
		 if( $i % 2 )
                $switch = "";
            else
                $switch = "background-color:#F5F5F5";
                 
		 $out .= "\n\t<tr style=\"height:50px;{$switch}\">" . "\n\t\<td>{$data['tld']}</td>" . "\n\t<td>{$data['hostname']}</td>". "\n\t<td>{$data['port']}</td>" . "\n\t<td>{$data['whoisserver']}</td>" . "\n\t<td><a href=\"{$vars['modulelink']}&action=delete\">Delete</a>";
		 $i++;
		 }
		 }
		 echo "
        <div id=\"clienttabs\">
        	<ul>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=active\"{$active_ac}>Registries Display</a></li>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=inactive\"{$active_in}>Add New</a></li>
        		<li class=\"tab\"><a href=\"addonmodules.php?module=COCCAReg&tab=summary&subtab=all\"{$active_al}>View Logs</a></li>
            </ul>
        </div>

        <div id=\"tab0box\" class=\"tabbox\">
            <div id=\"tab_content\" style=\"text-align:left;\">
                <table id=\"box-table-a\" summary=\"Registries\" width=\"100%\" cellspacing=\"0\">
                    <thead>
                        <tr style=\"height:50px;\">
                            <th scope=\"col\" width=\"250\" style=\"border-bottom: solid 3px #333\">TLD</th>
                            <th scope=\"col\" width=\"300\" style=\"border-bottom: solid 3px #333\">Hostname</th>
                            <th scope=\"col\" style=\"border-bottom: solid 3px #333\">Port</th>
                             <th scope=\"col\" style=\"border-bottom: solid 3px #333\">Whoisserver</th>
                            <th scope=\"col\" style=\"border-bottom: solid 3px #333\">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$out}
                    </tbody>
                </table>
            </div>
        </div>
    ";
            break; // This looks a bit confusing, but basically case "active" is the default
    } 

//Adding New TLDS
 if (isset($_POST["tldsubmit"]))
	 {
	 $tld = $_POST["extension"];
	 $registry = $_POST["registry"];
	 $hostname = $_POST["hostname"];
	 $port = $_POST["port"];
	 $protocol = $_POST["protocol"];
	 $username = $_POST["username"];
	 $password = $_POST["password"];
	 $timeout = $_POST["timeout"];
	 $whoisserver = $_POST["whoisserver"];
	 $debug = $_POST["debug"];
	 
	 insert_query("mod_COCCARegtlds", array("tld" => trim(strtolower($tld)),"hostname" => $hostname, "port" => $port, "protocol" => $protocol, "username" => $username, "password" => $password, "whoisserver" => $whoisserver, "debug" => $debug));
 	
}	

	
}
