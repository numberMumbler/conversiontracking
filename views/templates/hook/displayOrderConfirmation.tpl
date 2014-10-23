<!-- MODULE Conversion Tracking -->
{if isset($fbTrackingIds) AND $fbTrackingIds}
	{foreach from=$fbTrackingIds item=fbTrackingId}
		<!-- fb: {$fbTrackingId} -->
	{/foreach}
{/if}
{if isset($adwordsTrackingIds) AND $adwordsTrackingIds}
	{foreach from=$adwordsTrackingIds item=adwordsTrackingId}
		<!-- adwords: {$adwordsTrackingId} -->
	{/foreach}
{/if}
<!-- /MODULE Conversion Tracking -->