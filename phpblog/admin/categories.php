<?php
require_once '../config/database.php';
include 'includes/header.php';
include 'includes/sidebar.php';
$message = '';
$message_type = '';
if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    if (empty($name)) {
        $message = "Category name is required!";
        $message_type = "danger";
    } else {
        // Prepare SQL statement 
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");

        // Execute with parameters 
        $stmt->execute([$name, $description]);

        // Success message 
        $message = "Category added successfully!";
        $message_type = "success";
    }
}
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"> <i class="bi bi-tags"></i> Categories Management </h1>
        </div>

        <div id="messageContainer">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria
                        label="Close"></button>
                </div>
            <?php endif; ?>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Add New Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label for="categoryName" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" id="categoryName" name="name">
                    </div>

                    <div class="col-md-6">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <input type="text" class="form-control"
                            id="categoryDescription" name="description">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100"
                            name="add_category">
                            <i class="bi bi-plus-circle"></i> Add </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Categories</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all categories from database 
                            $stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at 
DESC");
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            // Display in table 
                            foreach ($categories as $category) : ?>


                                13
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><?php echo $category['name']; ?></td>
                                    <td><?php echo $category['description']; ?></td>
                                    <td><?php echo $category['created_at']; ?></td>
                                    <td>
                                        <a href="categories.php?edit_id=<?php echo $category['id']; ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>

                                        <a href="categories.php?del_id=<?php echo $category['id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php
                        if (isset($_GET['del_id'])) {
                            $del_id = $_GET['del_id'];
                            $stmt = $pdo->prepare("DELETE FROM categories WHERE id= ?");
                            $stmt->execute([$del_id]); // Execute delete 

                            // Success message 
                            $message = "Category deleted successfully!";
                            $message_type = "success";

                            echo "<script>window.location.href='categories.php';</script>";
                            exit();
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$edit_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $name = $category['name'];
            $description = $category['description'];
        }
    ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit New Category</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <label for="categoryName" class="form-label">Category Name *</label>
                        <input type="text" value="<?php echo $name; ?>" class="form-control" id="categoryName" name="name">
                    </div>

                    <div class="col-md-6">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <input type="text" value="<?php echo $description; ?>" class="form-control" id="categoryDescription" name="description">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-warning w-100"
                            name="update_category">
                            <i class="bi bi-pencil-square"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <?php
    }
    ?>
    </div>
    <?php
    if (isset($_POST['update_category'])) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $edit_id = $_GET['edit_id'];

        if (empty($name)) {
            $message = "Category name is required!";
            $message_type = "danger";
        } else {

            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");

            $stmt->execute([$name, $description, $edit_id]);

            $message = "Category updated successfully!";
            $message_type = "success";

            echo "<script>window.location.href = 'categories.php';</script>";
            exit();
        }
    }
    ?>
</main>
<?php include 'includes/footer.php'; ?>