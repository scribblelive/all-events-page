<?php

class AllEvents {
		
	// Set up all of your class variables.
	var $WebsiteId = "YOUR_WEBSITE_ID_HERE";
	var $APIToken = "YOUR_API_KEY_HERE";
	var $CurrentEvent = -1;
	var $AllEvents;
	var $LiveEvents;
	var $PastEvents;
	var $ScheduledEvents;
	var $RightNow;
	var $EventId;
	var $EventTitle;
	var $EventUrl;
	var $EventDescription;
	var $EventIsLive;
	var $EventStartDate;
	var $EventLastModifiedDate;
	
	// Make the call to the ScribbleLive API to get all of the events connected to a specific white label site, decode it, and send it to be parsed.
	function __construct() {
        	
        $FeedURL = file_get_contents('http://apiv1.scribblelive.com/website/' . $this->WebsiteId . '/events/?Token=' . $this->APIToken . '&Max=50&Format=json');

        $JSONData = json_decode($FeedURL);

        $this->ParseData($JSONData);

    }
	
	// Recieve the decoded JSON, and sort the events into arrays based on their start date.
	private function ParseData($pData) {
	    	
	    $this->NumberOfEvents = count($pData->Events);
		
		$this->AllEvents = $pData->Events;
		
		$this->RightNow = date("U");
		
		foreach ($this->AllEvents as $Event) {
			preg_match( '/\d{10}/', $Event->Start, $EventStartTime );
			if ($Event->IsLive == 1) {
				$this->LiveEvents[] = $Event;
			} elseif ( ($Event->IsLive == 0) && ($EventStartTime[0] < $this->RightNow) ) {
				$this->PastEvents[] = $Event;	
			} elseif ( ($Event->IsLive == 0) && ($EventStartTime[0] > $this->RightNow) ) {
				$this->ScheduledEvents[] = $Event;
			}
		}

	}
	
	// Recieve an event object and set variables for all of the event data you might want to display.
	private function SetupEventData($pEvent) {

		$EventData = $pEvent;
		
		$this->EventId = $EventData->Id;
		
		$this->EventTitle = $EventData->Title;
		
		$this->EventUrl = $this->GetUrl($EventData);
		
		$this->EventStartDate = $this->ParseDate($EventData->Start, "l F j Y g:ia");
		
		$this->EventLastModifiedDate = $this->ParseDate($EventData->LastModified, "l F j Y g:ia");
		
		if (isset($EventData->Description)) {
			$this->EventDescription = $EventData->Description;
		}
		
		$this->EventIsLive = $EventData->IsLive;
		
	}
	
	// The part of the loop that gets you the next event to display based on which type of event you would like to display.
	public function TheEvent($pWhichEvents) {
		$this->CurrentEvent++;
		if ($pWhichEvents == "All") {
			$this->SetupEventData($this->Events[$this->CurrentEvent]);
		} elseif ($pWhichEvents == "Live") {
			$this->SetupEventData($this->LiveEvents[$this->CurrentEvent]);
		} elseif ($pWhichEvents == "Past") {
			$this->SetupEventData($this->PastEvents[$this->CurrentEvent]);
		} elseif ($pWhichEvents == "Scheduled") {
			$this->SetupEventData($this->ScheduledEvents[$this->CurrentEvent]);
		}
	}
	
	// The part of the loop that finds out if there are any more events to display in the live events section.
	public function HasLiveEvents() {
		if ( $this->CurrentEvent + 1 < count($this->LiveEvents) ) {
			return true;
		} elseif ( $this->CurrentEvent + 1 == count($this->LiveEvents) ) {
			$this->ResetEvents();
		}
		return false;
	}
	
	// The part of the loop that finds out if there are any more events to display in the past events section.
	public function HasPastEvents() {
		if ( $this->CurrentEvent + 1 < count($this->PastEvents) ) {
			return true;
		} elseif ( $this->CurrentEvent + 1 == count($this->PastEvents) ) {
			$this->ResetEvents();
		}
		return false;
	}
	
	// The part of the loop that finds out if there are any more events to display in the scheduled events section.
	public function HasScheduledEvents() {
		if ( $this->CurrentEvent + 1 < count($this->ScheduledEvents) ) {
			return true;
		} elseif ( $this->CurrentEvent + 1 == count($this->ScheduledEvents) ) {
			$this->ResetEvents();
		}
		return false;
	}
	
	// Reset the loop when you have displayed the last event.
	private function ResetEvents() {
		$this->CurrentEvent = -1;
	}
	
	// Format the date into a more readable format. You can change the format by changing the second parameter when this function is called.
	private function ParseDate($pDate, $pFormat) {
		preg_match( '/\d{10}/', $pDate, $ParsedDate );
		$FormattedDate = date($pFormat, $ParsedDate[0]);
		return $FormattedDate;
	}
	
	// Loop through all of the white label sites the event is attached to and find the url that matches the id of the white label site you are looking for.
	private function GetUrl($pEventData) {
		foreach($pEventData->Websites as $Website) {
			if ($Website->Id == $this->WebsiteId) {
				return $Website->Url;
			}
		}
	}
	
	// These function return or echo information about each event for use in your HTML template.
	
	public function EventTitle() {
		echo $this->EventTitle;	
	}
	
	public function EventId() {
		echo $this->EventId;	
	}
	
	public function EventUrl() {
		echo $this->EventUrl;	
	}
	
	public function EventDescription() {
		echo $this->EventDescription;	
	}
	
	public function EventStartDate() {
		echo $this->EventStartDate;
	}
	
	public function EventLastUpdated() {
		echo $this->EventLastModifiedDate;
	}
	
	public function EventIsLive() {
		return $this->EventIsLive;
	}
	
}

?>