<style>
	.q-item {
		border-radius: 50px;
	}
</style>
<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM `questions` where id = " . $_GET['id'])->fetch_array(MYSQLI_ASSOC);
}
?>
<div class="card card-outline card-info">
	<div class="card-header">
		<h3 class="card-title"><?php echo isset($_GET['id']) ? "Update " : "Create New " ?>Question Response</h3>
	</div>
	<div class="card-body">
		<form action="" id="response-form">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">

			<div class="form-group">
				<label for="question_field" class="control-label">Question</label>
				<div class="input-group col-lg-6">
					<input type="text" id="question" class="form-control form-control-sm" data-original-title="" title="" name="question" value="<?php echo isset($qry['question']) ? $qry['question'] : ''; ?>" required>
				</div>
			</div>


			<div class="form-group" id="responce_create">
				<label for="response_message" class="control-label">Response Message</label>

				<?php
				if ($qry['response_id']) {

					$ResponceMessage = $conn->query("SELECT * FROM `responses` WHERE id IN (" . $qry['response_id'] . ")")->fetch_all(MYSQLI_ASSOC);

					foreach ($ResponceMessage as $key => $value) {
						echo '<textarea name="response_message[' . $value['id'] . ']" id="response_message" cols="30" rows="3" class="form-control" style="resize: none" required>' . $value['response_message'] . '</textarea>';
						echo '<a class="remove_responce" data-id="' . $value['id'] . '">Remove</a>';
					}
				} else {
					echo '<textarea name="response_message[]" id="response_message" cols="30" rows="3" class="form-control" style="resize: none" required></textarea>';
				}
				?>

				<a class="btn btn-sm btn-primary another_responce">Add Another Response</a>
			</div>
		</form>
	</div>
	<div class="card-footer">
		<button class="btn btn-flat btn-primary" form="response-form">Save</button>
		<a class="btn btn-flat btn-default" href="?page=responses">Cancel</a>
	</div>
</div>
<script>
	$(document).ready(function() {

		$('#response-form').submit(function(e) {
			e.preventDefault();
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_response",
				method: 'POST',
				data: $(this).serialize(),
				error: err => {
					console.log(err)
					alert_toast("An error occured", 'error');
					end_loader();
				},
				success: function(resp) {
					if (resp == 1) {
						location.href = "./?page=responses";
					} else {
						alert_toast("An error occured", 'error');
						end_loader();
					}
				}
			})
		})
	})
</script>