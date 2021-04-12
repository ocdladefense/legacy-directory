<?php

use Clickpdx\Core\Controller\ControllerBase;
use Ocdla\Member;
use Clickpdx\Core\User\ForceUser;
use Clickpdx\OAuth\OAuthGrantTypes;
use Clickpdx\SfRestApiRequestTypes;
use Clickpdx\Http\HttpRequest;
use Clickpdx\ResourceLoader;
use Clickpdx\Salesforce\RestApiAuthenticationException;
use Clickpdx\Salesforce\RestApiInvalidUrlException;
use Clickpdx\Salesforce\SoqlQueryBuilder;
use Clickpdx\Salesforce\SoqlBatchSelectQueryManager;
use Clickpdx\Salesforce\ForceToMySqlDataTransferManager;
use Clickpdx\OAuth\OAuthHttpAuthorizationService;
use Clickpdx\Salesforce\SObject;


class SfDirectoryController extends \SalesforceController
{

	public function debugForceApi($param="hen")
	{	
		static $retries = 0;
		if(++$retries > 3)
		{
			throw new \Exception("There was an error loading the OCDLA Membership Directory.");
		}
		
		$directoryService = $this->prepareApiServiceDebug(); // should we specify the endpoint here?
		print $directoryService;
		// exit;
		// $directoryService->authorize();
		// $directoryService->deleteAccessToken();
		
		try
		{
			$sfResult = $directoryService->executeQuery(
				sprintf(\setting('directory.queries.searchLastName'),$param)
			);
			$error = !$sfResult->count()?'No records found for this request.':"";
			$results = $sfResult->fetchAll();
		}
		catch(RestApiAuthenticationException $e)
		{
			$directoryService->authorize();
			$this->debugForceApi($param);
		}
		catch(RestApiInvalidUrlException $e)
		{
			$directoryService->authorize();
			$this->debugForceApi($param);
		}
		catch(\Exception $e)
		{
			$error = $e->getMessage();
		}

		/**
		 *
		 * We also have prependPath() and exists()
		 * functions.
		 */
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		return $this->render('search-results', array(
			'error' => $error,
			'query' => $directoryService->getSoqlQuery(),
			'link' => $instanceUrl,
			'results' => $results
		));
	}	
	
	// https://developer.salesforce.com/docs/atlas.en-us.soql_sosl.meta/soql_sosl/sforce_api_calls_sosl.htm
	private function getMemberData($contactId='003g000000bn9kj')
	{			
		$directoryService = $this->prepareApiService(); // should we specify the endpoint here?
		
		// $directoryService->authorize();
		// $directoryService->deleteAccessToken();

		try
		{
			return $directoryService->executeQuery(
				sprintf(\setting('directory.queries.forceProductionMemberInfo'),$contactId)
			);
		}
		catch(RestApiAuthenticationException $e)
		{
			$directoryService->authorize();
			$this->getMemberData($contactId);
		}
		catch(RestApiInvalidUrlException $e)
		{
			$directoryService->authorize();
			$this->getMemberData($contactId);
		}
		throw new \Exception("There was an error!");
	}


	public function addMember($contactId)
	{
		// print "Almost ready...";
		// exit;
		print "<h2>Give this member access to the LOD and OCDLA websites.</h2>";
		$members = $this->getMemberData($contactId);

		if($members->count()>1)
		{
			throw new \Exception("Trying to add data that resulted in more than one Salesforce Contact.");
		}
		$user = new ForceUser($members->getFirst());
		$user->save();
		exit;
	}



	public function showMember($contactId)
	{	
		$soql = sprintf(\setting('directory.queries.member'),$contactId);
		$sfResult = $this->doApiRequest($soql);
	
		if(!$sfResult->count())
		{
			// throw new \Exception('We could not find this member ID.');
			$error = "We could not find this member ID.";
		}
		
		/**
		 *
		 * We also have prependPath() and exists()
		 * functions.
		 */
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		
		return array(
			'#attached' => array(
				'css' => array(
					'/sites/default/modules/directory/css/directory.css'
				)
			),
			'#markup' => $this->render('member', array(
			'error' => $error,
			'contacts' => $sfResult->fetchAll()
			))
		);
	}
	



	public function searchForm() {
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		
		return array(
			'#attached' => array(
				'css' => array(
					'/sites/default/modules/directory/css/directory.css'
				)
			),
			'#markup' => $this->render('search-form', array(
				'showExpertLinks' => \setting('directory.showExpertWitnessLink',false),
				'user' 			=> $soql,
				'link'			=> $link,
				'error'			=> $error))
		);
	}



	public function getPicklistValues($objectName, $fieldApiName)
	{
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);

		$sobject = $this->doApiSchemaRequest($objectName);
		
		// $sobject = new SObject($json);
		$field = $sobject->getField($fieldApiName);
		
		// print $sobject;exit;
		// $results = $this->fieldPicklist($objectName,$fieldApiName);	
		// * objectInfo
	 // * doApiSchemaRequest
	 print $field->getPicklistAsHtmlSelect();
	 
		print "<pre>" . print_r($field->getPicklistValuesAll())."</pre>";
		exit;
		return array(
			'#attached' => array(
				'css' => array(
					'/sites/default/modules/directory/css/directory.css'
				)
			),
			'#markup' => $this->render('group-'.$fieldApiName, array(
				'query' 		=> $soql,
				'link'			=> $link,
				'error'			=> $error,
				'results'		=> $results))
		);
	}



	public function getDistinctValues($fieldApiName)
	{
		$title = array(
			'Ocdla_County__c' => 'Browse by County',
			'Ocdla_Areas_of_Interest_1__c' => 'Browse by Area of Interest',
			
		);
	
		/**
		 *
		 * We also have prependPath() and exists()
		 * functions.
		 */
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		// switch($field)

		if($fieldApiName == 'Ocdla_Areas_of_Interest_1__c')
		{
			$results = $this->fieldPicklist('Contact',$fieldApiName);
			// print "<pre>" . print_r($json,true)."</pre>";
			// exit;
			return array(
				'#attached' => array(
					'css' => array(
						'/sites/default/modules/directory/css/directory.css'
					)
				),
				'#markup' => $this->render('group-'.$fieldApiName, array(
					'query' 		=> $soql,
					'link'			=> $link,
					'error'			=> $error,
					'results'		=> $results))
			);
		}
		
		$params = $_GET;
		$validAndParams = array_flip(array('LastName','FirstName','Ocdla_Organization__c','Ocdla_Contact_Type__c','MailingCity','Ocdla_Occupation_Field_Type__c'));		
		
		$filteredAndParams = array_intersect_key($params,$validAndParams);
//		print $category;exit;
		$soql = \setting('directory.queries.category.'.$fieldApiName);

		try
		{
			if(empty($params)||!count($params))
			{
				throw new \Exception('You didn\'t specify any criteria in your search.');
			}
			
		
			$sfResult = $this->doApiRequest($soql);
			
			$results = $sfResult->fetchAll();
			
			if(!count($results))
			{
				// print $soql;
				throw new \Exception('Your query didn\'t return any results.');
			}

		}
		catch(\Exception $e)
		{
			$error = $e->getMessage();
		}
		

		return array(
			'#attached' => array(
				'css' => array(
					'/sites/default/modules/directory/css/directory.css'
				)
			),
			'#markup' => $this->render('group-'.$fieldApiName, array(
				'query' 		=> $soql,
				'link'			=> $link,
				'error'			=> $error,
				'results'		=> $results))
		);
		
	}


	public function searchContacts($op='LIKE')
	{	
		$params = $_GET;
		
		// $validOps = array('=','LIKE','
		
		$op = empty($_GET['op']) ? $op : $_GET['op'];
		
		$whereClause = null;
		
		$orParams = null;
		
		$andParams = null;
		
		$link = \setting('system.baseUrl');

		$validAndParams = array_flip(array('LastName','FirstName','Ocdla_Organization__c','Ocdla_Contact_Type__c','MailingCity','Ocdla_Occupation_Field_Type__c','Ocdla_County__c'));		
		$validAndParams = array_intersect_key($params,$validAndParams);
		$filteredAndParams = array_filter($validAndParams,function($val){
				return !empty($val);
			});
		

		// print(count($filteredAndParams));
		// print_r($filteredAndParams);exit;
		
		$validOrParams = array_flip(array('Interests'));				
		$filteredOrParams = array_intersect_key($params,$validOrParams);
		
		$interest = $filteredOrParams['Interests'];
		$hasInterestParams = !empty($interest);
		
		$interestParams = array(
		 'Ocdla_Areas_of_Interest_1__c',
		 'Ocdla_Areas_of_Interest_2__c',
		 'Ocdla_Areas_of_Interest_3__c',
		 'Ocdla_Areas_of_Interest_4__c',
		 'Ocdla_Areas_of_Interest_5__c',		 
		);
		
		$processInterests = function($colName) use ($interest) {
				return $colName . "='".  $interest  ."'";
		};
		
		$soql = \setting('directory.queries.baseSearch');
		


		if($op == 'STARTS WITH')
		{
			$andParams = array_map(function($colVal,$colName){
				return $colName . " LIKE '".  $colVal  ."%'";
			},$filteredAndParams,array_keys($filteredAndParams));
		}		
		else //if($op == 'LIKE')
		{
			$andParams = array_map(function($colVal,$colName){
				return $colName . " LIKE '%".  $colVal  ."%'";
			},$filteredAndParams,array_keys($filteredAndParams));
		}
		
		// print(count($andParams));
		// print_r($andParams);exit;
			
		$andParamsString = implode($andParams,' AND ');
		
		if($hasInterestParams)
		{
			$orParams = array_map($processInterests,$interestParams);
			$orParamsString = implode($orParams,' OR ');
		}
		
		$where = $andParamsString;
		
		$memberOrExpert = $_GET['IncludeExperts'] === '1' ? '(Ocdla_Current_Member_Flag__c=True OR Ocdla_Is_Expert_Witness__c=True)' : 'Ocdla_Current_Member_Flag__c=True';
			
		// print $where;exit;
		if(!empty($orParams))
		{
			$where .= count($andParams) ? ' AND (' . $orParamsString . ')' : '(' . $orParamsString . ')';
		}
		$soql .= empty($where)? $where.' '.$memberOrExpert.' ORDER BY LastName ASC, FirstName ASC' : $where.' AND '.$memberOrExpert.' ORDER BY LastName ASC, FirstName ASC';

		try
		{
			if(empty($params)||!count($params))
			{
				throw new \Exception('You didn\'t specify any criteria in your search.');
			}
			
		
			$sfResult = $this->doApiRequest($soql);
			
			$results = $sfResult->fetchAll();
			
			if(!count($results))
			{
				// print $soql;
				throw new \Exception('Your query didn\'t return any results.');
			}

		}
		catch(\Exception $e)
		{
			$error = $e->getMessage();
		}
		
		// print $this->youSearchedFor($filteredAndParams,$filteredOrParams);exit;
		/**
		 *
		 * We also have prependPath() and exists()
		 * functions.
		 */
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		return array(
			'#attached' => array(
				'css' => array(
					'/sites/default/modules/directory/css/directory.css?v=1'
				)
			),
			'#markup' => $this->render('search-results', array(
				'breadcrumbs'	=> array('Home','Membership Directory Browse/Search'),
				'debug' 		=> isset($_GET['debug']) ? true : false,
				'query' 		=> $soql,
				'numResults' => count($results),
				'youSearchedFor' => $this->youSearchedFor($filteredAndParams,$filteredOrParams),
				'link'			=> $link,
				'error'			=> $error,
				'results'		=> $results))
		);
	}

	private function youSearchedFor(array $searchCriteria, $searchCriteria2)
	{
		$criteria = empty($searchCriteria2) ? $searchCriteria : ($searchCriteria + $searchCriteria2);
		
		$fieldValues = array_map(function($v,$k){
			return $k . ': '.$v;
		},$criteria,array_keys($criteria));
		
		return implode(',', $fieldValues);
	}

	
	public function findMembersByLastName($LastName=null)
	{	
		try
		{
			if(empty($LastName))
			{
				throw new \Exception('No Last Name was specified.');
			}
		}
		catch(\Exception $e)
		{
			$soql = sprintf(\setting('directory.queries.searchLastName'),$LastName);
		
			$sfResult = $this->doApiRequest($soql);
		
			$error = !$sfResult->count()?'No records found for this request.':"";

			$results = $sfResult->fetchAll();
		}
		/**
		 *
		 * We also have prependPath() and exists()
		 * functions.
		 */
		$this->addTemplateLocation(
			'sites/default/modules/directory/templates'
		);
		return $this->render('search-results', array(
			// 'query' => $directoryService->getSoqlQuery(),
			// 'link' => $instanceUrl,
			'results' => $results
		));
	}
	



	
}