<?php

// Include the PHP file that makes all of the PHP tags on this page possible.

include("allevents.php");

$AllEvents = new AllEvents();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>All Events</title>
<style type="text/css">
	body {
		margin: 10px;
		padding: 0;
		font-family: Verdana, Arial, sans-serif;
	}
	h3 {
		background: #ececec;
		padding: 5px;
	}
	ul.EventList {
		margin: 0;
		padding: 0;
		list-style-type: none;
	}
	ul.EventList li {
		margin: 0;
		padding: 15px 10px	;
		list-style-type: none;
		border-bottom: 1px solid #ccc;
	}
	ul.EventList li:hover {
		background: #ececec;
	}
	ul.EventList li h4 {
		margin: 0;
	}
	ul.EventList li h4 a {
		text-decoration: none;
		color: #000;
	}
	ul.EventList li h4 a:hover {
		text-decoration: underline;
	}
	ul.EventList li p {
		font-size: 12px;
		margin-bottom: 0;
	}
</style>
</head>

<body>

<h1>All Events</h1>

<!-- Check if there are live events to display before proceeding. This is repeated below for scheduled events and past events, feel free to change it up for each category. -->
<?php if ($AllEvents->HasLiveEvents()) : ?>
	
	<h3>Live Events</h3>
	
	<ul id="LiveEvents" class="EventList">
		
		<!-- Set up a loop that displays all of the live events. -->
		<?php while ($AllEvents->HasLiveEvents()) : $AllEvents->TheEvent("Live"); ?>
			
			<!-- The template for each event. It gets it's data from the tags at the bottom of the allevents.php file. -->
			<li id="<?php $AllEvents->EventId(); ?>">
				
				<h4><a href="<?php $AllEvents->EventUrl(); ?>" title="<?php $AllEvents->EventTitle(); ?>"><?php $AllEvents->EventTitle(); ?></a></h4>
				
				<p><?php $AllEvents->EventDescription(); ?></p>
				
				<p>Event last updated: <?php $AllEvents->EventLastUpdated(); ?></p>
				
			</li>
			
		<?php endwhile ; ?>
		
	</ul>
	
<!-- If there are no live events. -->
<?php else : ?>
	
	<p>No Live Events Found</p>
	
<?php endif ; ?>

<?php if ($AllEvents->HasScheduledEvents()) : ?>
	
	<h3>Upcoming Events</h3>
	
	<ul id="ScheduledEvents" class="EventList">
		
		<?php while ($AllEvents->HasScheduledEvents()) : $AllEvents->TheEvent("Scheduled"); ?>
			
			<li id="<?php $AllEvents->EventId(); ?>">
				
				<h4><a href="<?php $AllEvents->EventUrl(); ?>" title="<?php $AllEvents->EventTitle(); ?>"><?php $AllEvents->EventTitle(); ?></a></h4>
				
				<p><?php $AllEvents->EventDescription(); ?></p>
				
				<p>Event starts: <?php $AllEvents->EventStartDate(); ?></p>
				
			</li>
			
		<?php endwhile ; ?>
		
	</ul>
	
<?php else : ?>
	
	<p>No Scheduled Events Found</p>
	
<?php endif ; ?>

<?php if ($AllEvents->HasPastEvents()) : ?>
	
	<h3>Past Events</h3>
	
	<ul id="EventList" class="EventList">
	
		<?php while ($AllEvents->HasPastEvents()) : $AllEvents->TheEvent("Past"); ?>
		
			<li id="<?php $AllEvents->EventId(); ?>">
				
				<h4><a href="<?php $AllEvents->EventUrl(); ?>" title="<?php $AllEvents->EventTitle(); ?>"><?php $AllEvents->EventTitle(); ?></a></h4>
				
				<p><?php $AllEvents->EventDescription(); ?></p>
				
				<p>Event last updated: <?php $AllEvents->EventLastUpdated(); ?></p>
				
			</li>
			
		<?php endwhile ; ?>
		
	</ul>
	
<?php else : ?>
	
	<p>No Past Events Found</p>
	
<?php endif ; ?>

</ul>

</body>
</html>
