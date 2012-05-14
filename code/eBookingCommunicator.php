<?php

class eBookingCommunicator extends RestfulService {
	public static $eBookingUrl = '';
	public static $eBookingLogin = '';
	public static $eBookingPassword = '';
	
	function __construct($expiry = 60) {
		parent::__construct(self::$eBookingUrl, $expiry);
		
		$this->basicAuth(self::$eBookingLogin, self::$eBookingPassword);
	}
	
	public function getResources($includeGroups = false) {
		$resources = null;
	
		$allowed_locales = Translatable::get_allowed_locales();
		$translatable_fields = array("Name");
		$add_fields = array();
		
		foreach ($translatable_fields as $field) {
			foreach ($allowed_locales as $locale) {
				$add_fields[] = $field . '_' . $locale;
			}
		}
		
		for ($retries=0;$retries<3;$retries++) {
			try {
				$xml = $this->request('Resource' . '?add_fields=' . implode(',', $add_fields), 'GET');
				$resources = $this->getValues($xml->getBody(), 'Resource');

				foreach ($resources as $resource) {
					if ($resource->ParentID == 0)
						$resources->remove($resource);
					else {
						foreach ($translatable_fields as $field) {
							if ($resource->hasField($field . '_' . i18n::get_locale())) {
								$resource->setField($field, $resource->getField($field . '_' . i18n::get_locale()));
							}
						}
					}
				}
				break;
			}
			catch (Exception $e) {
				Debug::log("Unable to get resources! (" . $e->getMessage() . "), retry nr. " . $retries);
				continue;
			}
		}
						
		return $resources;
	}
	
	public function getResourcesAsDropdownMap($fieldName = 'Name') {
		$resources = $this->getResources();
			
		if ($resources === null) {
			Debug::log('getResourcesAsDropdownMap(...) unable to get resources!');
			return array('' => '');
		}		
		
		$map = array('' => '');
		
		foreach ($resources as $resource) {
			$map += array($resource->ID => $resource->getField($fieldName));
		}
		
		return $map;
	}
	
	public function getResourceByID($id) {
		$resource = null;

		$allowed_locales = Translatable::get_allowed_locales();
		$translatable_fields = array("Name");
		$add_fields = array();
		
		foreach ($translatable_fields as $field) {
			foreach ($allowed_locales as $locale) {
				$add_fields[] = $field . '_' . $locale;
			}
		}		
		
		for ($retries=0;$retries<3;$retries++) {
			try {
				$xml = $this->request('Resource/' . $id . '?add_fields=' . implode(',', $add_fields), 'GET');

				$xml_body = new SimpleXMLElement($xml->getBody());
				$data = array();
				$this->getRecurseValues($xml_body,$data);
				$resource = new ArrayData($data);
				foreach ($translatable_fields as $field) {
					if ($resource->hasField($field . '_' . i18n::get_locale())) {
						$resource->setField($field, $resource->getField($field . '_' . i18n::get_locale()));
					}
				}
				break;
			}
			catch (Exception $e) {
				Debug::log('Unable to get resource by id ' . $id . '! (' . $e->getMessage() . '), retry nr. ' . $retries);
				continue;
			}
		}
						
		return $resource;
	}
	
	public function getBookings() {
		$bookings = null;
		
		for ($retries=0;$retries<3;$retries++) {
			try {

				$xml = $this->request('Booking', 'GET');
				$bookings = $this->getValues($xml->getBody(), 'Booking');			
				break;
			}
			catch (Exception $e) {
				Debug::log('Unable to get bookings! (' . $e->getMessage() . '), retry nr. ' . $retries);
				continue;
			}
		}
		
		return $bookings;
	}	
	
	public function getBookingByID($id) {
		$booking = null;
		
		for ($retries=0;$retries<3;$retries++) {
			try {
				$xml = $this->request('Booking/' . $id, 'GET');

				$xml_body = new SimpleXMLElement($xml->getBody());
				$data = array();
				$this->getRecurseValues($xml_body,$data);			
				$booking = new ArrayData($data);
				break;
			}
			catch (Exception $e) {
				Debug::log('Unable to get booking by id ' . $id . '! (' . $e->getMessage() . '), retry nr. ' . $retries);
				continue;
			}
		}
		
		return $booking;
	}
	
	public function getResourceByBookingID($id) {
		$booking = $this->getBookingByID($id);
		if ($booking === null || !$booking->hasField('ResourceID'))
			return null;
		
		return $this->getResourceByID($booking->ResourceID);
	}	
	
	public function getBookingsByResourceID($id) {
		$bookings = $this->getBookings();
		$result = new DataObjectSet();

		if ($bookings === null) {
			Debug::log('getBookingsByResourceID(...) unable to get bookings!');
			return null;
		}
			
		foreach ($bookings as $booking) {
			if ($booking->ResourceID == $id)
				$result->push($booking);
		}
		
		return $result;
	}
	
	public function updateBooking($id, $startDate=null, $endDate=null, $location=null, $cancelled=false) {
		$old_cache_expire = $this->cache_expire;
		$this->cache_expire = -1;	
		
		$data = '<Booking>';
		if ($startDate != null)
			$data .= "<Start>{$startDate}</Start>";
		if ($endDate != null) 
			$data .= "<End>{$endDate}</End>";
		if ($location != null && $location != 0)
			$data .= "<ResourceID>{$location}</ResourceID>";

		if ($cancelled != null && $cancelled == true)
			$data .= "<Status>Rejected</Status>";
		else
			$data .= "<Status>Accepted</Status>";
			
		$data .= '</Booking>';
		
		$xml = $this->request('Booking/' . $id, 'PUT', $data);
			
		$this->cache_expire = $old_cache_expire;		
		
		Debug::log("Updating booking with id " . $id . ", data " . $data);
	}
	
	public function removeBooking($id) {
		$old_cache_expire = $this->cache_expire;
		$this->cache_expire = -1;	
		
		$data = '<Booking><Status>Rejected</Status></Booking>';
		
		$xml = $this->request('Booking/' . $id, 'PUT', $data);
		
		$this->cache_expire = $old_cache_expire;		
		
		//Debug::log("Removed booking with id " . $id . ".");
	}
	
	public function createBooking($start, $end, $resourceID, $book = true) {		
		$data = 'Start=' . rawurlencode($start) . '&';
		$data .= 'End=' . rawurlencode($end) . '&';
		$data .= 'ResourceID=' . $resourceID . '&';
		$data .= 'TypeID=1' . '&';
		if ($book)
			$data .= 'Status=Accepted';
		else
			$data .= 'Status=Pending';
				
		$old_cache_expire = $this->cache_expire;
		$this->cache_expire = -1;
		
		$xml = $this->request('Booking', 'POST', $data); 
		
		$this->cache_expire = $old_cache_expire;
		
		try {
			$simple = new SimpleXMLElement($xml->getBody());
		
			return $simple->ID;
		}
		catch(Exception $e) {
			return null;
		}
	}
}

?>
