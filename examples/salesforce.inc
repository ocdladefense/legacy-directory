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
	
	// print $svc;
	
	
	$apiReq=$svc->getHttpRequest(SfRestApiRequestTypes::REST_API_REQUEST_TYPE_SOQL);
	$apiReq->addHttpHeader('Authorization',"OAuth {$svc->getAccessToken()}");

	$apiResp = $svc->sendRequest($apiReq);
	$sfResult = new Clickpdx\Salesforce\SfResult($apiResp);
	if($sfResult->hasError())
	{
		throw new \Exception("There was an error executing the SOQL query: {$sfResult->getErrorMsg()}.");
	}
	return $sfResult;
}


function sfObjectInfo($object)
{
	$svc = ResourceLoader::getResource('sfMemdir');
	$svc->setEndpoint('sobject',array('object'=>$object));
	$apiReq=$svc->getHttpRequest(SfRestApiRequestTypes::REST_API_REQUEST_TYPE_ENTITY);
	$apiReq->addHttpHeader('Authorization',"OAuth {$svc->getAccessToken()}");
	$apiResp = $svc->sendRequest($apiReq);
	// print $apiResp;exit;
	$apiInfo = json_decode($apiResp->read(),true);
	// print entity_toString($apiInfo);exit;
	if($apiInfo[0]['errorCode'])
	{
		$error = $apiInfo[0];
		throw new \Exception("There was an error executing the query: \n
			errorCode: {$error['errorCode']}&nbsp;&nbsp;
			message: {$error['message']}.");
	}
	return $apiInfo;
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


