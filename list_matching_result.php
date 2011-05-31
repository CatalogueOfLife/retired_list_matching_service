<?php

require_once("process.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	  <title>Listing Matching Service</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	  <link href="styles/style.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="main">
			<div class="title">
				<img class="title" src="images/banner.jpg">
				<p class="title">List Matching Service</p>
			</div>
			<div class="container">
				<?php if (!isset($error)): ?>
					<form method="POST" name="form" id="form" enctype="multipart/form-data" action="download.php">
						<?php if (count($matches) > 0): ?>
							<input type="hidden" name="csv_value" value="<?php echo $csv_value ?>" />
							<div class="group">
								<table class="group">
									<tr>
										<td><p class="group">Matched Species</p></td>
										<td style="text-align: right;"><input type="submit" name="download" value="Download to file" class="downloadbutton" /></td>
									</tr>
								</table>
							</div>
							<?php $row_class = "even"; ?>
							<table class="list">
								<thead>
									<th>Your Data</th>
									<th>Scientific Name</th>
									<?php if (!$only_accepted_names): ?>
										<th>Status</th>
									<?php endif; ?>
								<thead>
								<tbody>
									<?php for ($i = 0; $i < count($matches); $i++): ?>
										<?php $row_class = ($row_class == "odd" ? "even" : "odd");?>
										<tr class="<?php echo $row_class; ?>">
											<td><?php echo $matches[$i]["data_inputed"]; ?></td>
											<td><?php echo $matches[$i]["formated_taxon"]; ?></td>
											<?php if (!$only_accepted_names): ?>							
												<td><?php echo $matches[$i]["status"]; ?></td>
											<?php endif; ?>
										</tr>
									<?php endfor; ?>
								</tbody>
							</table>
						<?php endif; ?>
						<?php if (count($unmatches) > 0): ?>
							<div class="group">
								<p class="group">Unmatched Species</p>
							</div>
							<?php $row_class = "even"; ?>
							<table class="list">
								<tbody>
									<?php for ($i = 0; $i < count($unmatches); $i++): ?>
										<?php $row_class = ($row_class == "odd" ? "even" : "odd");?>
										<tr class="<?php echo $row_class; ?>">
											<td><?php echo $unmatches[$i]; ?></td>
										</tr>
									<?php endfor; ?>
								</tbody>
							</table>
						<?php endif; ?>
						<br />
					</form>
				<?php else: ?>
					<div class="error">
						<br /><p><?php echo $error; ?></p>
						<br /><input type="button" class="trybutton" value="Try again" onclick="javascript:history.go(-1);"/>
						<br /><br />
					</div>
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>