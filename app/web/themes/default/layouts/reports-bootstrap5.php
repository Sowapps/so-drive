<?php
/* @var string $reportStream */
if( !isset($reportStream) ) {
	$reportStream = 'global';
}

foreach( getReports($reportStream) as $type => $typeReports ) {
	$type = ($type === 'error') ? 'danger' : $type;
	foreach( $typeReports as $report ) {
		$rType = $type;
		if( $rType === 'danger' && !$report['severity'] ) {
			$rType = 'warning';
		}
		echo '
<div class="alert alert-' . $rType . ' ' . $report['domain'] . ' alert-dismissible fade show">
	' . $report['report'] . '
	<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>';
	}
}

$reportStream = null;
