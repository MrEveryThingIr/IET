  <!-- Footer -->
  <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container text-center">
		
            <p class="mb-0">
			 <!-- Logo and Slogan -->
        <div class="navbar-brand d-flex flex-column align-items-center">
            <img src="assets/images/taamkarbrand.png" alt="Tamkaar Logo" class="img-fluid" style="max-height: 80px;">
            <h2 class="text-green-400 fw-bold mt-2" style="font-size: 1.2rem;">تامکار تبلور توان ایرانی</h2>
        </div>
			</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"

    ></script>

    

<script src="<?php __DIR__.'../../assets/js/js.js' ?>"></script>

<script>
    function confirmDelete(projectId) {
        if (confirm("Are you sure you want to delete this project?")) {
            window.location.href = `delete_project.php?id=${projectId}`;
        }
    }
</script>

</body>
</html>
