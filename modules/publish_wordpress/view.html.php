<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$tag = 'z-schedule-manager';
?>
<h2><?php echo HCM::__('Shortcode'); ?></h2>
<code class="hc-p2 hc-mt2">
[<?php echo $tag; ?>]
</code>

<h2><?php echo HCM::__('Shortcode Options'); ?></h2>

<ul class="hc-ml3">
	<li>
		<h3 class="hc-underline">range</h3>
	</li>
	<li>
		<?php echo HCM::__('Defines which date range options are available in the front end.'); ?>
	</li>
	<li>
		<?php echo HCM::__('Default'); ?>: <em>"week, month"</em>
	</li>

	<li>
		<ul class="hc-ml3">
			<li>
				<strong>week</strong>
			</li>

			<li>
				<strong>month</strong>
			</li>

			<li>
				<strong>day</strong>
			</li>

			<li>
				<strong>now</strong>
			</li>

			<li>
				<?php echo HCM::__('You can place several options separated by commas. The order the options appear in the shortcode will define their order in the front end view too.'); ?>
			</li>
			<li class="hc-p2">
				<code class="hc-p2">
				[<?php echo $tag; ?> type="now,week,month"]
				</code>
			</li>
		</ul>
	</li>
</ul>