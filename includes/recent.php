<?php
/** 
### License

Copyright 2018 Spinitron, LLC

Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.


* Plugin Name: WsPin
* Description:  Call Spinitron API
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*  */

include __DIR__ . '/getClient.php';
/** @var SpinitronApiClient $client */

/** recent function returns formatted html for recent playlist
 *  @param count is how many songs to return
 *  @param show_id is show to return playlist from, blank is all
 */
function recent($count,$show_id) {
	global $client;

	$dateTimeZoneLA = new DateTimeZone("America/Los_Angeles"); // timezone
	// get cached playlist from API
	$params = array('count' => $count);
	if (!empty($show_id)) {
		$params['show_id'] = $show_id;
	}
	$result = $client->search('spins', $params);
	foreach ($result['items'] as $spin) { 
/*		$stime2 = new DateTime($spin['start']);
		$stime = new DateTime($spin['start']);
		$offset = -timezone_offset_get($dateTimeZoneLA, $stime)/3600;
			// get interval between here and UTC
		$stime->sub(new DateInterval("PT".$offset."H")); 
			// fixes timezone
			// 'start' has -8:00 as timezone,
			// but DateInterval changes to UTC 
*/
			$stime = new DateTime($spin['start']);
			$stime = $stime->setTimezone(new DateTimeZone($spin['timezone']));
		$stime = $stime->format('g:ia') ;
       	 $artist  = htmlspecialchars($spin['artist'],  ENT_NOQUOTES); 
       	 $song    = htmlspecialchars($spin['song'],    ENT_NOQUOTES); 
         $release = htmlspecialchars($spin['release'], ENT_NOQUOTES);

 	 $rstring .= <<<EOT
    <p class='spin_recent'>$stime
        <strong>$artist</strong>
        <em>'$song'</em>
        from $release</p>
EOT;
	$tz = $spin['timezone'];
	}

	$tdate = gmdate('H:i:s');
	return $rstring ;
}
