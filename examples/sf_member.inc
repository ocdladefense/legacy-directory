<?php
use Clickpdx\OAuth\OAuthGrantTypes;
use Clickpdx\SfRestApiRequestTypes;
use Clickpdx\Http\HttpRequest;
use Clickpdx\ResourceLoader;

function sfQuery($query)
{
	// Instantiate the SOQL app.
		// --> which retrieves the application data from the session store.
	$svc = ResourceLoader::getResource('sfMemdir');

	$svc->soqlQuery($query);
	$apiReq=$svc->getHttpRequest(SfRestApiRequestTypes::REST_API_REQUEST_TYPE_SOQL);
	$apiReq->addHttpHeader('Authorization',"OAuth {$svc->getAccessToken()}");


	$apiResp = $svc->sendRequest($apiReq);
	$apiInfo = json_decode($apiResp->read(),true);
	if($apiInfo['errorCode'])
	{
		$svc->resetOAuthSession();
	}
	return $apiResp;
}


function sfObjectsInfo($object)
{
	$svc = ResourceLoader::getResource('sfMemdir');
	$svc->setEndpoint('sobjects');
	$apiReq=$svc->getHttpRequest(SfRestApiRequestTypes::REST_API_REQUEST_TYPE_ENTITY);
	$apiReq->addHttpHeader('Authorization',"OAuth {$svc->getAccessToken()}");
	$apiResp = $svc->sendRequest($apiReq);
	$apiInfo = json_decode($apiResp->read(),true);
	if($apiInfo['errorCode'])
	{
		$svc->resetOAuthSession();
	}
	return $apiInfo;
}


function sfObjectInfo($object)
{
	$svc = ResourceLoader::getResource('sfMemdir');
	$svc->setEndpoint('sobject',array('object'=>$object));
	$apiReq=$svc->getHttpRequest(SfRestApiRequestTypes::REST_API_REQUEST_TYPE_ENTITY);
	$apiReq->addHttpHeader('Authorization',"OAuth {$svc->getAccessToken()}");
	$apiResp = $svc->sendRequest($apiReq);
	$apiInfo = json_decode($apiResp->read(),true);
	if($apiInfo['errorCode'])
	{
		$svc->resetOAuthSession();
	}
	return $apiInfo;
}


function show_accounts($instance_url,$access_token)
{
    $query = "SELECT Name, Id from Account LIMIT 100";
    $url = "$instance_url/services/data/v20.0/query?q=" . urlencode($query);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);
    curl_close($curl);
		// print $json_response; exit;
    $response = json_decode($json_response, true);

    $total_size = $response['totalSize'];

    $sResp =  "$total_size record(s) returned<br /><br />";
    foreach ((array) $response['records'] as $record) {
        $sResp.= ($record['Id'] . ", " . $record['Name'] . "<br />");
    }
		return $sResp;
}

function create_account($name, $instance_url, $access_token) {
    $url = "$instance_url/services/data/v20.0/sobjects/Account/";

    $content = json_encode(array("Name" => $name));

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 201 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    
    echo "HTTP status $status creating account<br/><br/>";

    curl_close($curl);

    $response = json_decode($json_response, true);

    $id = $response["id"];

    echo "New record id $id<br/><br/>";

    return $id;
}

function show_account($id, $instance_url, $access_token) {
    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    echo "HTTP status $status reading account<br/><br/>";

    curl_close($curl);

    $response = json_decode($json_response, true);

    foreach ((array) $response as $key => $value) {
        echo "$key:$value<br/>";
    }
    echo "<br/>";
}

function update_account($id, $new_name, $city, $instance_url, $access_token) {
    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

    $content = json_encode(array("Name" => $new_name, "BillingCity" => $city));

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token",
                "Content-type: application/json"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 204 ) {
        die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    echo "HTTP status $status updating account<br/><br/>";

    curl_close($curl);
}

function delete_account($id, $instance_url, $access_token) {
    $url = "$instance_url/services/data/v20.0/sobjects/Account/$id";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
            array("Authorization: OAuth $access_token"));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

    curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 204 ) {
        die("Error: call to URL $url failed with status $status, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    echo "HTTP status $status deleting account<br/><br/>";

    curl_close($curl);
}