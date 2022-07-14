<?php get_header('inc');

$lorem = new Query(array(
	"type"=>"courses",
	"status" => array("publish"),
	"limit"		=> 2,
	"offset"	=> 2
));
?>

<main>
	<div class="container">
		<div class="row">
			<?php foreach($lorem->data as $key => $val): ?>
				<div class="col-4 py-3">
					<div class="card">
						<h5 class="card-title">
							<?php echo $val->title; ?>
						</h5>
						<div class="card-body">
							<?php echo $val->description; ?>
						</div>
						<div class="card-footer">
							<a href="/cours/<?php echo $val->permalink; ?>">En savoir plus</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php get_footer('inc'); ?>