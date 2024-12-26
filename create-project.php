<?php
include "partials/admin/header.php"; 
include "partials/admin/navbar.php"; 
?>

<?php
if (isPostRequest()) {
    // Collect and sanitize POST data
    $data = [
        'title' => getPostData('title'),
        'orderer_name' => getPostData('orderer_name'),
        'project_manager' => getPostData('project_manager'),
        'order_no' => getPostData('order_no'),
        'product_code' => getPostData('product_code'),
        'start_date' => getPostData('start_date'),
        'completed_at' => getPostData('completed_at'),
        'description' => getPostData('description'),
    ];

    // Handle file upload
    $data['orderer_brand'] = handleFileUpload('orderers_brands', 'orderer_brand');

    if ($data['orderer_brand'] === false) {
        echo "<p class='text-danger'>File upload failed. Please try again.</p>";
        exit;
    }

    // Create project
    if ($project_id = $project->create($data)) {
        redirect("index.php");
        exit; // Always exit after a redirect
    } else {
        echo "<p class='text-danger'>Project creation failed. Please try again.</p>";
    }
}
?>


<main class="container my-5 ">
<h2>ایجاد پروژه ی جدید</h2>
    <form   method="post" enctype="multipart/form-data">
	

        <!-- Project Title -->
        <div class="mb-3">
            <label for="title" class="form-label">عنوان پروژه *</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter project title" required>
        </div>
        
        <!-- Orderer Name -->
        <div class="mb-3">
            <label for="orderer_name" class="form-label">نام کارفرما *</label>
            <input type="text" class="form-control" id="orderer_name" name="orderer_name" placeholder="Enter orderer's name" required>
        </div>

        <!-- Orderer Brand -->
        <div class="mb-3">
            <label for="orderer_brand" class="form-label"> بارگذاری برند کارفرما </label>
            <input type="file" class="form-control" id="orderer_brand" name="orderer_brand" placeholder="Enter orderer's brand (if any)">
        </div>

        <!-- Project Manager -->
        <div class="mb-3">
            <label for="project_manager" class="form-label">مدیر پروژه  *</label>
            <input type="text" class="form-control" id="project_manager" name="project_manager" placeholder="Enter project manager's name" required>
        </div>
        
        <!-- Product Code -->
        <div class="mb-3">
            <label for="order_no" class="form-label">شماره سفارش</label>
            <input type="text" class="form-control" id="order_no" name="order_no" placeholder="Enter order number">
        </div>

        <!-- Product Code -->
        <div class="mb-3">
            <label for="product_code" class="form-label">کد کالا</label>
            <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Enter product code">
        </div>


		      <div class="mb-3">
            <label for="start_date" class="form-label">تاریخ شروع</label>
            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="YYYY-MM-DD">
        </div>

		<div class="mb-3">
            <label for="completed_at" class="form-label">وضعیت( در حال انجام یا تمام شده در تاریخ فلان)</label>
            <input type="text" class="form-control" id="completed_at" name="completed_at" placeholder="YYYY-MM-DD یا در حال انجام یا تاریخ اتمام ">
			
        </div>
	<div class="mb-3">
            <label for="description" class="form-label">دیگر توضیحات</label>
       <textarea name="description" id="description"><?php echo htmlspecialchars(getPostData('description') ?? '', ENT_QUOTES); ?></textarea>

        </div>
     

        <!-- Submit and Cancel Buttons -->
        <div class="d-flex justify-content-start mt-4">
            <button type="submit" class="btn btn-success me-2">ایجاد پروژه</button>
            <a href="index.php" class="btn btn-secondary">لغو</a>
        </div>
    </form>
</main>

<?php include "partials/admin/footer.php"; ?>
