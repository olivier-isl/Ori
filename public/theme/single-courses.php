<?php get_header('inc'); ?>
	<main>
		<div class="container px-0">
			<div class="row px-0 mx-0">
				<div class="row px-0 mx-0">
					<div class="col-8">
						<div class="card">
							vidéo
						</div>
					</div>
					<div class="col-4">
						<div class="card">
							<?php 
								$lessons = new Query(array(
									'type' => 'lessons'
								));
							?>
							<ul class="nav flex-column">
								<?php foreach($lessons->data as $val): ?>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo $val->permalink; ?>"><?php echo $val->title; ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="row px-0 mx-0">
					<div class="col-12"></div>
				</div>
			</div>
		</div>
	</main>
<?php get_footer('inc'); ?>
