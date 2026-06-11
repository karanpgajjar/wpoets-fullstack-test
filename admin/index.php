<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/SectionModel.php';
require_once __DIR__ . '/../models/SlideModel.php';

$sectionModel = new SectionModel($db);
$slideModel   = new SlideModel($db);

$sections = $sectionModel->getAllAdmin();
$slides   = $slideModel->getAllAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | WPoets</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #c4351e;
            --brand-secondary: #11324d;
            --brand-fourth: #64b4c8;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f6f6f6;
        }

        .navbar-brand,
        h1,
        h2,
        h3,
        h4,
        h5,
        .modal-title {
            font-family: 'Titillium Web', sans-serif;
        }

        .navbar {
            background: var(--brand-secondary) !important;
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .nav-tabs .nav-link.active {
            color: var(--brand-secondary);
            border-bottom-color: var(--brand-secondary);
            font-weight: 600;
        }

        .btn-primary {
            background: var(--brand-secondary);
            border-color: var(--brand-secondary);
        }

        .btn-primary:hover {
            background: #0d253a;
            border-color: #0d253a;
        }

        .btn-danger {
            background: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .badge-active {
            background: #56bd5b;
        }

        .badge-inactive {
            background: #adadad;
        }

        .table thead th {
            background: var(--brand-secondary);
            color: #fff;
            font-family: 'Titillium Web', sans-serif;
        }

        .section-card {
            border-left: 4px solid var(--brand-fourth);
        }

        .toast-container {
            z-index: 1100;
        }

        #loadingOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        #loadingOverlay.show {
            display: flex;
        }
    </style>
</head>

<body>
    <div id="loadingOverlay">
        <div class="spinner-border text-light" style="width:3rem;height:3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="bi bi-layers-half me-2"></i>WPoets Admin
            </a>
            <div class="ms-auto">
                <a href="../index.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-eye me-1"></i>View Site
                </a>
            </div>
        </div>
    </nav>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="appToast" class="toast align-items-center border-0 text-white" role="alert">
            <div class="d-flex">
                <div class="toast-body fw-semibold" id="toastMsg"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <div class="container-fluid px-4 py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0" style="color:var(--brand-secondary)">
                <i class="bi bi-grid-1x2-fill me-2"></i>Content Management
            </h4>
        </div>

        <ul class="nav nav-tabs mb-4" id="adminTabs">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sectionsTab">
                    <i class="bi bi-columns-gap me-1"></i>Sections
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#slidesTab">
                    <i class="bi bi-images me-1"></i>Slides
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="sectionsTab">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between py-3">
                        <span class="fw-semibold">All Sections</span>
                        <button class="btn btn-primary btn-sm" id="btnAddSection">
                            <i class="bi bi-plus-circle me-1"></i>Add Section
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="sectionsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Label</th>
                                        <th>Slug</th>
                                        <th>Icon Path</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sections as $s): ?>
                                        <tr data-id="<?= (int) $s['id'] ?>">
                                            <td><?= (int) $s['id'] ?></td>
                                            <td class="fw-semibold"><?= htmlspecialchars($s['label']) ?></td>
                                            <td><code><?= htmlspecialchars($s['slug']) ?></code></td>
                                            <td class="text-muted small"><?= htmlspecialchars($s['icon_path']) ?></td>
                                            <td><?= (int) $s['sort_order'] ?></td>
                                            <td>
                                                <span class="badge <?= $s['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                                    <?= $s['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-section me-1"
                                                    data-id="<?= (int) $s['id'] ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete-section"
                                                    data-id="<?= (int) $s['id'] ?>"
                                                    data-label="<?= htmlspecialchars($s['label']) ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="slidesTab">
                <div class="card shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between py-3">
                        <span class="fw-semibold">All Slides</span>
                        <button class="btn btn-primary btn-sm" id="btnAddSlide">
                            <i class="bi bi-plus-circle me-1"></i>Add Slide
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="slidesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Section</th>
                                        <th>Category</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($slides as $sl): ?>
                                        <tr data-id="<?= (int) $sl['id'] ?>">
                                            <td><?= (int) $sl['id'] ?></td>
                                            <td>
                                                <span class="badge" style="background:var(--brand-fourth)">
                                                    <?= htmlspecialchars($sl['section_label']) ?>
                                                </span>
                                            </td>
                                            <td class="small text-uppercase text-muted"><?= htmlspecialchars($sl['category']) ?></td>
                                            <td style="max-width:240px" class="text-truncate"><?= htmlspecialchars($sl['title']) ?></td>
                                            <td class="small text-muted"><?= htmlspecialchars($sl['image_path']) ?></td>
                                            <td><?= (int) $sl['sort_order'] ?></td>
                                            <td>
                                                <span class="badge <?= $sl['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                                                    <?= $sl['is_active'] ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-slide me-1"
                                                    data-id="<?= (int) $sl['id'] ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete-slide"
                                                    data-id="<?= (int) $sl['id'] ?>"
                                                    data-title="<?= htmlspecialchars($sl['title']) ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="sectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--brand-secondary)">
                    <h5 class="modal-title text-white" id="sectionModalTitle">Add Section</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="sectionId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sectionLabel" placeholder="e.g. Learning">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sectionSlug" placeholder="e.g. learning">
                        <div class="form-text">Lowercase, hyphens only. Auto-filled from label.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon Path</label>
                        <input type="text" class="form-control" id="sectionIconPath" placeholder="assets/svg/DL-learning.svg">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" class="form-control" id="sectionSortOrder" value="0" min="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="sectionIsActive">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="sectionErrors" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnSaveSection">
                        <i class="bi bi-save me-1"></i>Save Section
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="slideModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--brand-secondary)">
                    <h5 class="modal-title text-white" id="slideModalTitle">Add Slide</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="slideId">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Section <span class="text-danger">*</span></label>
                        <select class="form-select" id="slideSectionId">
                            <option value="">— select section —</option>
                            <?php foreach ($sections as $s): ?>
                                <option value="<?= (int) $s['id'] ?>"><?= htmlspecialchars($s['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Eyebrow <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="slideCategory" placeholder="e.g. DIGITAL LEARNING INFRASTRUCTURE">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="slideTitle" rows="2" placeholder="Slide headline…"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Link URL</label>
                        <input type="url" class="form-control" id="slideLinkUrl" placeholder="https://…" value="#">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Image Path</label>
                        <input type="text" class="form-control" id="slideImagePath" placeholder="assets/images/DL-Learning-1.jpg">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" class="form-control" id="slideSortOrder" value="0" min="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="slideIsActive">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div id="slideErrors" class="alert alert-danger d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnSaveSlide">
                        <i class="bi bi-save me-1"></i>Save Slide
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirm Delete</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteConfirmText" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-sm" id="btnConfirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"></script>
</body>

</html>