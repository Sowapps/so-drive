<?php
/**
 * @var HtmlRendering $rendering
 * @var HttpController $controller
 * @var HttpRequest $request
 * @var HttpRoute $route
 * @var FormToken $formToken
 *
 * @var int $uploadMaxSizeBytes
 * @var string $uploadMaxSizeHuman
 * @var string[] $files
 */

use Orpheus\Form\FormToken;
use Orpheus\InputController\HttpController\HttpController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\Rendering\HtmlRendering;

$rendering->useLayout('page_skeleton');
?>

<div class="row justify-content-center">
	<div class="col col-md-6">
		<form id="FormUpload" method="post" enctype="multipart/form-data">
			<?php
			echo $formToken;
			$this->display('reports-bootstrap5');
			?>
			<div class="mb-3">
				<label for="InputFiles" class="form-label">The files to upload</label>
				<input id="InputFiles" class="form-control" type="file" name="file[]" multiple>
				<div class="form-text">
					The maximum allowed size is <?php echo $uploadMaxSizeHuman; ?>B.
				</div>
			</div>
			<div class="text-end">
				<button class="btn btn-primary" type="submit">Upload</button>
			</div>
		</form>
		
		<?php
		if( $files ) {
			?>
			<div class="mt-4">
				<h2>Uploads</h2>
				<div class="list-group mt-3">
					<?php
					foreach( $files as $file ) {
						?>
						<a class="list-group-item d-inline-flex justify-content-between align-items-center" href="<?php echo $file->link; ?>" download>
							<?php printf('%s (%s)', $file->label, $file->size); ?>
							<i class="fa-solid fa-download"></i>
						</a>
						<?php
					}
					?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", () => {
		const $form = document.getElementById("FormUpload");
		$form.addEventListener("submit", () => {
			$form.classList.add("processing");
		});
	});
</script>

