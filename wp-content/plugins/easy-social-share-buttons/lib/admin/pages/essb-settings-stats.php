<?php
global $wpdb;
global $stats;

$mode = isset ( $_GET ["mode"] ) ? $_GET ["mode"] : "1";
$month = isset($_GET['essb_month']) ? $_GET['essb_month'] : '';

$today = date ( 'Y-m-d' );
$today_month = date ( 'Y-m' );

// print $essb;
?>

<div class="essb">
	<div class="wrap">
	
		<?php if ($mode == "1") { ?>
	
		<div class="col1_1">

			<button class="button-secondary"
				onclick="window.location='admin.php?page=essb_settings&tab=stats&mode=1';">Overall
				Statistics</button>
			<button class="button-primary"
				onclick="window.location='admin.php?page=essb_settings&tab=stats&mode=2';">Month
				Statistics</button>
		</div>
	
		<?php
			
			$stats->essb_stat_admin_by_networks ();
			
			?>
	
		<div class="col1_1">
			<h3>Activity by Content</h3>
		
		<?php
			
			$stats->essb_stat_admin_detail_by_post ();
			
			?>
		
		</div>
		
		<?php } ?>
		
		<?php if ($mode == "2") { ?>
				<div class="col1_1">

			<button class="button-primary"
				onclick="window.location='admin.php?page=essb_settings&tab=stats&mode=1';">Overall
				Statistics</button>
			<button class="button-secondary"
				onclick="window.location='admin.php?page=essb_settings&tab=stats&mode=2';">Month
				Statistics</button>
		</div>
		<form name="general_form" method="get" action="admin.php"
			enctype="multipart/form-data">
			<input type="hidden" id="page" name="page" value="essb_settings" /> <input
				type="hidden" id="tab" name="tab" value="stats" /> <input
				type="hidden" id="mode" name="mode" value="2" />
			<div style="width: 620px;">
				<div class="metabox-holder">
					<div class="postbox">

						<h3>
							<span style="font-size: 16px;">Click stats by month</span>
						</h3>
						<div class="inside">

							<table border="0" width="590" cellpadding="5" cellspacing="0">

								<col width="50%" />
								<col width="50%" />



								<tr class="table-border-bottom">
									<td>Month:</td>
									<td><select name="essb_month" id="essb_month"
										class="input-element">
											<option value=""></option>
									<?php 
									
									$stats->generate_months_dropdown_values($month);
									
									?>
								</select></td>
								</tr>
								<tr>
									<td colspan="2"><input type="submit" id="essb_button"
										name="essb_button" value="Show Report" class="button-primary" /></td>
								</tr>


							</table>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		<!--  stats  -->
		
		<?php if ($month != '') { ?>
		
		<h4>Activity by date of month</h4>
		
		<div class="col1_1">
		
		<?php $stats->generate_bar_graph_month($month);?>
		
		</div>
		
		<div class="col1_1">
			<h4>Activity by content</h4>
		
		<?php
			
			$stats->essb_stat_admin_detail_by_post ($month);
			
			?>
		
		</div>
		
		
		<?php } ?>
		
		<?php } ?>
	</div>
</div>