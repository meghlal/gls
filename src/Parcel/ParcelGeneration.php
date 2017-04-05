<?php
namespace GLS\Parcel;

use Curl;

class ParcelGeneration
{
	/**
	 * user name – request it from GLS
	 * Type : String
	 * Example : 2080060960
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 20)
	 */
	protected $UserName;
	
	/**
	 * Password – request it from GLS
	 * Type : String
	 * Example : API1234
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 20)
	 */		
	protected $Password;
	
	/**
	 * Customerid – request it from GLS
	 * Type : String
	 * Example : 2080060960
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 20)
	 */
	protected $Customerid;
	
	/**
	 * Contactid – request it from GLS
	 * Type : String
	 * Example : 208a144Uoo
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 20)
	 */
	protected $Contactid;
	
	/**
	 * ShipmentDate
	 * Type : String
	 * Example : 20130911
	 * Comment : Format:YYYYMMDD
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 8)
	 */
	protected $ShipmentDate;
	
	/**
	 * Reference – request it from GLS
	 * Type : String
	 * Example : API1234
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 * @Assert\NotBlank
	 * @Assert\Length(max = 20)
	 */
	protected $Reference;
	
	/**
	 * Addresses 
	 * Type : List
	 * Example : API1234
	 * Comment : -
	 * Mandatory : Min. 1
	 * Max.Length : -
	 *  .Name1 String Masters Inc. Yes 40
	 *	.Name2 String No 40
	 *	.Name3 String No 40
	 *	.Street1 String Randomstreet 5 Yes 40
	 *	.CountryNum String 208 ISO 3166-1 numeric Yes 3
	 *	.ZipCode String 6000 Yes 10
	 *	.City String Kolding Yes 40
	 *	.Contact String Per Jensen No 40
	 *	.Email String email@email.dk No 100
	 *	.Phone String +4576331100 No 40
	 *	.Mobile String +4512345678 No 40
	 */
	protected $Addresses;
	
	/**
	 * Parcels 
	 * Type : List
	 * Example : 
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 *	Name		Type	Example		Comment		Mandatory		Max.Length
	 * 	.Weight 	Number 	5.2 					Yes 			50.0
	 *	.Reference 	String 	Parcel ref. 			No 				50
	 *	.Comment 	String 	2 sweaters 				No 				40
	 *	.Cashservice Number 1560.25 				No
	 *	.AddOnLiabilityService Number 8564.00 		No
	 */
	protected $Parcels;
	
	/**
	 * Services
	 * Type : List
	 * Example : 
	 * Comment : -
	 * Mandatory : Yes
	 * Max.Length : -
	 */
	protected $Services;
	
	
	
	/* 
	* API endpoint
	*/
	protected $APIendpoint = 'http://api.gls.dk/ws/DK/V1/CreateShipment';
	
	public function setUsername($username)
	{
		$this->UserName = $username;
		return $this;
	}
	
	public function setPassword($password)
	{
		$this->Password = $password;
		return $this;
	}
	
	public function setCustomerid($customerid)
	{
		$this->Customerid = $customerid;
		return $this;
	}
	
	public function setContactid($contactid)
	{
		$this->Contactid = $contactid;
		return $this;
	}
	
	public function setShipmentDate($shipmentdate)
	{
		$this->ShipmentDate = $shipmentdate;
		return $this;
	}
	
	public function setReference($reference)
	{
		$this->Contactid = $contactid;
		return $this;
	}
	
	public function setAddresses($addresses,$type)
	{
		/*
		*	Types
		*		Delivery
		*		Pickup
		*		AlternativeShipper
		*/
		$f=0;
		$err = array();
		$adata = array();
		if(isset($addresses['Name1']) && $addresses['Name1']!='' )
		{
			if(strlen($addresses['Name1']) <=40)
				$adata['Name1'] = $addresses['Name1'];
			else
				array_push($err, 'Name1 must be less than euual to 40');	
		}
		else
		{
			$f++;
			array_push($err, 'Name1 cannot be blank');
		}
		
		if(isset($addresses['Name2']) && $addresses['Name2']!='')
			if(strlen($addresses['Name2']) <=40)
				$adata['Name2'] = $addresses['Name2'];
			else
				array_push($err, 'Name2 must be less than euual to 40');
		
		if(isset($addresses['Name3']) && $addresses['Name3']!='')
			if(strlen($addresses['Name3']) <=40)
				$adata['Name3'] = $addresses['Name3'];
			else
				array_push($err, 'Name3 must be less than euual to 40');
				
				
		if(isset($addresses['Street1']) && $addresses['Street1']!='')
		{
			if(strlen($addresses['Street1']) <=40)
				$adata['Street1'] = $addresses['Street1'];
			else
				array_push($err, 'Street1 must be less than euual to 40');
		}
		else
		{
			$f++;
			array_push($err, 'Street1 cannot be blank');
		}
			
		if(isset($addresses['CountryNum']) && $addresses['CountryNum']!='')
		{
			if(strlen($addresses['CountryNum']) <=3)
				$adata['CountryNum'] = $addresses['CountryNum'];
			else
				array_push($err, 'CountryNum must be less than euual to 3');
		}
		else
		{
			$f++;
			array_push($err, 'CountryNum cannot be blank');
		}
			
		if(isset($addresses['ZipCode']) && $addresses['ZipCode']!='')
		{
			if(strlen($addresses['ZipCode']) <=10)
				$adata['ZipCode'] = $addresses['ZipCode'];
			else
				array_push($err, 'ZipCode must be less than euual to 20');
		}	
		else
		{
			$f++;
			array_push($err, 'ZipCode cannot be blank');
		}
			
		if(isset($addresses['City']) && $addresses['City']!='')
		{
			if(strlen($addresses['City']) <=40)
				$adata['City'] = $addresses['City'];
			else
				array_push($err, 'City must be less than euual to 40');
		}
		else
		{
			$f++;
			array_push($err, 'City cannot be blank');
		}
		
		if(isset($addresses['Contact']) && $addresses['Contact']!='')
			if(strlen($addresses['Contact']) <=40)
				$adata['Contact'] = $addresses['Contact'];
			else
				array_push($err, 'Contact must be less than euual to 40');
				
				
		if(isset($addresses['Email']) && $addresses['Email']!='')
			if(strlen($addresses['Email']) <=100)
				$adata['Email'] = $addresses['Email'];
			else
				array_push($err, 'Email must be less than euual to 100');
				
			
		if(isset($addresses['Phone']) && $addresses['Phone']!='')
			if(strlen($addresses['Phone']) <=40)
				$adata['Phone'] = $addresses['Phone'];
			else
				array_push($err, 'Phone must be less than euual to 40');
				
			
		if(isset($addresses['Mobile']) && $addresses['Mobile']!='')
			if(strlen($addresses['Mobile']) <=40)
				$adata['Mobile'] = $addresses['Mobile'];
			else
				array_push($err, 'Mobile must be less than euual to 40');
		
		if($f == 0)
		{
			if(trim(strtolower($type)) == 'delivery')
			{
				$this->Addresses['Delivery'] = $adata;
				return $this;
			}
			else if(trim(strtolower($type)) == 'pickup')
			{
				$this->Addresses['Pickup'] = $adata;
				return $this;
			}
			else if(trim(strtolower($type)) == 'alternativeshipper')
			{
				$this->Addresses['AlternativeShipper'] = $adata;
				return $this;
			}
			else
			{
				$f++;
				array_push($err, 'Invalid address type');
				return array('status'=>'error', 'error_message' => $err);
			}
			
		}
		else
		{
			return array('status'=>'error', 'error_message' => $err);
		}
	}
	
	public function setParcels($parcels)
	{
		$f=0;
		$err = array();
		$pdata = array();
		if(isset($parcels['Weight']) && $parcels['Weight']!='' )
		{
			$weight = (float)$parcels['Weight'];
			if($weight <= 50.0)
				$pdata['Weight'] = $parcels['Name1'];
			else
				array_push($err, 'Weight must be less than euual to 50.0 kg');	
		}
		else
		{
			$f++;
			array_push($err, 'Weight cannot be blank');
		}
		
		if(isset($parcels['Reference']) && $parcels['Reference']!='')
			if(strlen($parcels['Reference']) <=50)
				$pdata['Reference'] = $parcels['Reference'];
			else
				array_push($err, 'Reference must be less than euual to 50');
		
		if(isset($parcels['Comment']) && $parcels['Comment']!='')
			if(strlen($parcels['Comment']) <=40)
				$pdata['Comment'] = $parcels['Comment'];
			else
				array_push($err, 'Comment must be less than euual to 40');
		
		if(isset($parcels['Cashservice']) && $parcels['Cashservice']!='')
				$pdata['Cashservice'] = round($parcels['Cashservice'],2);
		
		if(isset($parcels['AddOnLiabilityService']) && $parcels['AddOnLiabilityService']!='')
				$pdata['AddOnLiabilityService'] = round($parcels['AddOnLiabilityService'],2);	
		
		if($f == 0)
		{			
			$this->Parcels = $pdata;
			return $this;
		}
		else
		{
			return array('status'=>'error', 'error_message' => $err);
		}
		
	}
	
	public function setServices($services)
	{
		$f=0;
		$err = array();
		$sdata = array();
		
		if(isset($services['ShopDelivery']) && $services['ShopDelivery']!='')
			if(strlen($services['ShopDelivery']) <=10)
				$sdata['ShopDelivery'] = $services['ShopDelivery'];
			else
				array_push($err, 'ShopDelivery must be less than euual to 10');
				
		if(isset($services['NotificationEmail']) && $services['NotificationEmail']!='')
			if(strlen($services['NotificationEmail']) <=100)
				$sdata['NotificationEmail'] = $services['NotificationEmail'];
			else
				array_push($err, 'NotificationEmail must be less than euual to 100');
				
		if(isset($services['Deposit']) && $services['Deposit']!='')
			if(strlen($services['Deposit']) <=100)
				$sdata['Deposit'] = $services['Deposit'];
			else
				array_push($err, 'Deposit must be less than euual to 100');
				
		if(isset($services['ShopReturn']) && $services['ShopReturn']!='')
			if(strlen($services['ShopReturn']) <=1)
				$sdata['ShopReturn'] = $services['ShopReturn'];
			else
				array_push($err, 'ShopReturn must be less than euual to 40');
				
		if($f == 0)
		{			
			$this->Services = $sdata;
			return $this;
		}
		else
		{
			return array('status'=>'error', 'error_message' => $err);
		}		
	}
	
	public function getData($format = 'json')
	{
		$data = array();	
		if($this->UserName !='' && $this->Password !='' && $this->Customerid !='' && $this->Contactid !='' && $this->ShipmentDate !='' && !empty($this->Addresses) && !empty($this->Parcels) && !empty($this->Services))
		{
			$data['UserName'] = $this->UserName;
			$data['Password'] = $this->Password;
			$data['Customerid'] = $this->Customerid;
			$data['Contactid'] = $this->Contactid;
			
			if($data['ShipmentDate'] !='')
				$data['ShipmentDate'] = $this->ShipmentDate;
				
			if($data['Reference'] !='')
				$data['Reference'] = $this->Reference;
			
			$data['Addresses'] = $this->Addresses;
			$data['Parcels'] = $this->Parcels;
			$data['Services'] = $this->Services;
			
			if($format == 'json')
				return json_encode($data);
				
		}
		else
			return null;
	}
	
	public function sendRequest()
	{
		$reqData = $this->getData('json');
		try
		{
			$curl = new Curl();
			$curl->setHeader('Content-Type', 'application/json');
			$curl->post($this->APIendpoint, $reqData);
			if ($curl->error) {
				return $curl->error_code;
			}
			else {
				return $curl->response;
			}
		}
		catch(Exception $e)
		{
			return $e;
		}
		
	}
	
}