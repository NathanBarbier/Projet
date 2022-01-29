<?php if($pageName != 'map.php') { ?>
	<!-- main section div -->
	</div>
<!-- side bar div -->
</div>
<?php } ?>

	<!-- Cookie Modal -->
	<div class="modal fade position-absolute top-25" id="cookiemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h5 class="modal-title" id="staticBackdropLabel">Confidentialité</h5>
					<p class="mt-3">Ce site utilise des cookies pour vous fournir la meilleure expérience de navigation possible. En continuant sur ce site, vous acceptez l'utilisation des cookies.</p>
				</div>
				<div class="modal-footer">
					<button id="cookieDetails" type="button" class="btn btn-outline-secondary">Détails</button>
					<button type="button" class="btn btn-outline-success consentToCookie">Accepter</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade position-absolute top-25" id="cookiedetailsmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h5 class="modal-title" id="staticBackdropLabel">Détails sur les cookies</h5>
					<div class="accordion mt-3" id="accordionExample">
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Necessary
								</button>
							</h2>
							<div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
								<div class="accordion-body">
								Certains cookies sont nécessaires au bon fonctionnement du site. Cette catégorie n'inclue que les cookies qui garantissent les fonctionnalités de base et les fonctionnalités de sécurité du site Web. Ces cookies ne stockent aucune information personnelle.
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button id="cookieback" type="button" class="btn btn-outline-secondary">Retour</button>
					<button type="button" class="btn btn-outline-success consentToCookie">Accepter</button>
				</div>
			</div>
		</div>
	</div>

    <script>
		const AJAX_URL = <?php echo json_encode(AJAX_URL); ?>;
	</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="<?= ROOT_URL ?>script.js"></script>
	<script src="<?= JS_URL ?>visiteur/cookies.js"></script>

    <!-- Tooltip activation -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>
</html>