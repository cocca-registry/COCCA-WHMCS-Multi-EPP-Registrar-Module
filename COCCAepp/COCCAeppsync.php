<?

# Bring in Database Constants
require_once dirname(__FILE__) . '/../../../dbconnect.php';
# Setup include dir
$include_path = ROOTDIR . '/modules/registrars/COCCAepp';
set_include_path($include_path . PATH_SEPARATOR . get_include_path());
# Include EPP stuff we need
require_once 'COCCAepp.php';
# Additional functions we need
require_once ROOTDIR . '/includes/functions.php';
# Registrar Functions
require_once ROOTDIR . '/includes/registrarfunctions.php';

# Grab module parameters
$params = getregistrarconfigoptions('COCCAepp');

# Let's Go...
try {
	$client = _cozaepp_Client();

	# Pull list of domains which are registered using this module
	$queryresult = mysql_query("SELECT domain FROM tbldomains WHERE registrar = 'COCCAepp'");
	while($data = mysql_fetch_array($queryresult)) {
		$domains[] = trim(strtolower($data['domain']));
	}

	# Loop with each one
	foreach($domains as $domain) {
		sleep(1);

		# Query domain
		$output = $client->request($xml='<?xml version="1.0" encoding="UTF-8" standalone="no"?>
   <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <command>
       <info>
         <domain:info
          xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
				<domain:name>'.$domain.'</domain:name>
			</domain:info>
		</info>
		<clTRID>'.mt_rand().mt_rand().'</clTRID>
	</command>
</epp>');

		$doc= new DOMDocument();
		$doc->loadXML($output);
		$coderes = $doc->getElementsByTagName('result')->item(0)->getAttribute('code');
		if($coderes == '1000') {
			if( $doc->getElementsByTagName('status')) {
				if($doc->getElementsByTagName('status')->item(0)) {
					$statusres = $doc->getElementsByTagName('status')->item(0)->getAttribute('s');
					$createdate = substr($doc->getElementsByTagName('crDate')->item(0)->nodeValue,0,10);
					$nextduedate = substr($doc->getElementsByTagName('exDate')->item(0)->nodeValue,0,10);
				} else {
					$status = "Domain $domain not registered!";
					continue;
				}
			}
		} else {
			echo "Domain check on $domain not successful";
			continue;
		}


		# This is the template we going to use below for our updates
		$querytemplate = "UPDATE tbldomains SET status = '%s', registrationdate = '%s', expirydate = '%s', nextduedate = '%s' WHERE domain = '%s'";

		# Check status and update
		if ($statusres == "ok") {
			mysql_query(sprintf($querytemplate,"Active",
					mysql_real_escape_string($createdate),
					mysql_real_escape_string($nextduedate),
					mysql_real_escape_string($nextduedate),
					mysql_real_escape_string($domain)
			));
			echo "Updated $domain expiry to $nextduedate\n";

		} elseif ($statusres == "serverHold") {

		} elseif ($statusres == "expired" || $statusres == "pendingDelete" || $statusres == "inactive") {
			mysql_query(sprintf($querytemplate,"Expired",
					mysql_real_escape_string($createdate),
					mysql_real_escape_string($nextduedate),
					mysql_real_escape_string($nextduedate),
					mysql_real_escape_string($domain)
			));
			echo "Domain $domain is EXPIRED (Registration: $createdate, Expiry: $nextduedate)\n";

		} else {
			echo "Domain $domain has unknown status '$statusres'\n";
		}
	}

} catch (Exception $e) {
	echo("ERROR: ".$e->getMessage()."\n");
	exit;
}

?>
