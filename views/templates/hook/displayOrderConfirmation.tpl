<!-- MODULE Conversion Tracking -->
<section>
{if !isset($orderTotal)}{assign var='orderTotal' value=1.0}{/if}
{if isset($fbTrackers) AND $fbTrackers}
	<section>
	{foreach from=$fbTrackers item=fbTracker}
		<script>{literal}
		(function() {
		var _fbq = window._fbq || (window._fbq = []);
		if (!_fbq.loaded) {
		var fbds = document.createElement('script');
		fbds.async = true;
		fbds.src = '//connect.facebook.net/en_US/fbds.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(fbds, s);
		_fbq.loaded = true;
		}
		})();
		window._fbq = window._fbq || [];
		window._fbq.push(['track', {/literal}'{$fbTracker['id']}'{literal}, {
			'value':{/literal}'{$orderTotal}'{literal},'currency':'USD'
		}]);
		{/literal}</script>
		<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev={$fbTracker['id']}&amp;cd[value]={$orderTotal}&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
	{/foreach}
	</section>
{/if}
{if isset($adwordsTrackers) AND $adwordsTrackers}
	<section>
	{foreach from=$adwordsTrackers item=adwordsTracker}
		<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = {$adwordsTracker['id']};
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "{$adwordsTracker['label']}";
		var google_conversion_value = {$orderTotal};
		var google_conversion_currency = "USD";
		var google_remarketing_only = false;
		/* ]]> */
		</script>
		<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
		</script>
		<noscript>
		<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/{$adwordsTracker['id']}/?value={$orderTotal}&amp;currency_code=USD&amp;label={$adwordsTracker['label']}&amp;guid=ON&amp;script=0"/>
		</div>
		</noscript>
	{/foreach}
	</section>
{/if}
{if isset($bandpageTrackers) AND $bandpageTrackers}
	<section>
	{foreach from=$bandpageTrackers item=bandpageTracker}
		<script type="text/javascript">
		/*<![CDATA[ */
		var BANDPAGE_RETAILER = 'dreamermedia';
		var BANDPAGE_ORDER_ID = '{$orderId}';
		 
		var BANDPAGE_AMOUNT_1 = '{$orderTotal}';
		var BANDPAGE_CAT_1 = 'merchandise';
		/* ]]> */
		</script>
	{/foreach}
	</section>
{/if}
</section>
<!-- /MODULE Conversion Tracking -->