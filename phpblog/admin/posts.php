<?php
require_once '../config/database.php';
include './includes/header.php';
include './includes/sidebar.php';
$message = '';
$message_type = '';
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
if (isset($_GET['del_id'])) {
    $del_id = $_GET['del_id'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$del_id]);
    echo "<script>window.location.href = 'posts.php';</script>";
    exit();
}
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2"> <i class="bi bi-file-text"></i> Posts Management </h1>
            <a href="addpost.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Post
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Posts</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo $post['id']; ?></td>
                            <td><?php echo $post['title']; ?></td>
                            <td><?php echo $post['category_id']; ?></td>

                            <td>
                                <?php if ($post['status'] == 'published'): ?>
                                    <span class="badge bg-success">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Draft</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo date('M j', strtotime($post['created_at'])); ?></td>

                            <td>
                                <a href="editpost.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                    Edit </a>

                                <a href="posts.php?del_id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"> Delete </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php include './includes/footer.php'; ?>