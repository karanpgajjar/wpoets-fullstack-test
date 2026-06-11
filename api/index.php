<?php

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../models/SectionModel.php';
require_once __DIR__ . '/../models/SlideModel.php';

header('Content-Type: application/json');

$method   = $_SERVER['REQUEST_METHOD'];
$resource = $_GET['resource'] ?? '';
$id       = isset($_GET['id']) ? (int) $_GET['id'] : null;

$sectionModel = new SectionModel($db);
$slideModel   = new SlideModel($db);

function json_out(array $payload, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

function read_body(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}


if ($resource === 'sections') {
    switch ($method) {
        case 'GET':
            if ($id) {
                $row = $sectionModel->getById($id);
                $row
                    ? json_out(['success' => true, 'data' => $row])
                    : json_out(['success' => false, 'message' => 'Section not found.'], 404);
            }
            json_out(['success' => true, 'data' => $sectionModel->getAllAdmin()]);

        case 'POST':
            $data   = read_body();
            $errors = $sectionModel->validate($data);
            if ($errors) json_out(['success' => false, 'errors' => $errors], 422);
            $newId = $sectionModel->create([
                'slug'       => $data['slug'],
                'label'      => $data['label'],
                'icon_path'  => $data['icon_path']  ?? '',
                'sort_order' => (int) ($data['sort_order'] ?? 0),
                'is_active'  => (int) ($data['is_active']  ?? 1),
            ]);
            $newId
                ? json_out(['success' => true, 'message' => 'Section created.', 'id' => $newId], 201)
                : json_out(['success' => false, 'message' => 'Failed to create section.'], 500);

        case 'PUT':
            if (!$id) json_out(['success' => false, 'message' => 'ID required.'], 400);
            $data   = read_body();
            $errors = $sectionModel->validate($data);
            if ($errors) json_out(['success' => false, 'errors' => $errors], 422);
            $ok = $sectionModel->update($id, [
                'slug'       => $data['slug'],
                'label'      => $data['label'],
                'icon_path'  => $data['icon_path']  ?? '',
                'sort_order' => (int) ($data['sort_order'] ?? 0),
                'is_active'  => (int) ($data['is_active']  ?? 1),
            ]);
            $ok
                ? json_out(['success' => true, 'message' => 'Section updated.'])
                : json_out(['success' => false, 'message' => 'Failed to update section.'], 500);

        case 'DELETE':
            if (!$id) json_out(['success' => false, 'message' => 'ID required.'], 400);
            $ok = $sectionModel->hardDelete($id);
            $ok
                ? json_out(['success' => true, 'message' => 'Section deleted.'])
                : json_out(['success' => false, 'message' => 'Failed to delete section.'], 500);

        default:
            json_out(['success' => false, 'message' => 'Method not allowed.'], 405);
    }
}



if ($resource === 'slides') {
    switch ($method) {
        case 'GET':
            if ($id) {
                $row = $slideModel->getById($id);
                $row
                    ? json_out(['success' => true, 'data' => $row])
                    : json_out(['success' => false, 'message' => 'Slide not found.'], 404);
            }
            json_out(['success' => true, 'data' => $slideModel->getAllAdmin()]);

        case 'POST':
            $data   = read_body();
            $errors = $slideModel->validate($data);
            if ($errors) json_out(['success' => false, 'errors' => $errors], 422);
            $newId = $slideModel->create([
                'section_id' => (int) $data['section_id'],
                'category'   => $data['category'],
                'title'      => $data['title'],
                'link_url'   => $data['link_url']    ?? '#',
                'image_path' => $data['image_path']  ?? '',
                'sort_order' => (int) ($data['sort_order'] ?? 0),
                'is_active'  => (int) ($data['is_active']  ?? 1),
            ]);
            $newId
                ? json_out(['success' => true, 'message' => 'Slide created.', 'id' => $newId], 201)
                : json_out(['success' => false, 'message' => 'Failed to create slide.'], 500);

        case 'PUT':
            if (!$id) json_out(['success' => false, 'message' => 'ID required.'], 400);
            $data   = read_body();
            $errors = $slideModel->validate($data);
            if ($errors) json_out(['success' => false, 'errors' => $errors], 422);
            $ok = $slideModel->update($id, [
                'section_id' => (int) $data['section_id'],
                'category'   => $data['category'],
                'title'      => $data['title'],
                'link_url'   => $data['link_url']    ?? '#',
                'image_path' => $data['image_path']  ?? '',
                'sort_order' => (int) ($data['sort_order'] ?? 0),
                'is_active'  => (int) ($data['is_active']  ?? 1),
            ]);
            $ok
                ? json_out(['success' => true, 'message' => 'Slide updated.'])
                : json_out(['success' => false, 'message' => 'Failed to update slide.'], 500);

        case 'DELETE':
            if (!$id) json_out(['success' => false, 'message' => 'ID required.'], 400);
            $ok = $slideModel->hardDelete($id);
            $ok
                ? json_out(['success' => true, 'message' => 'Slide deleted.'])
                : json_out(['success' => false, 'message' => 'Failed to delete slide.'], 500);

        default:
            json_out(['success' => false, 'message' => 'Method not allowed.'], 405);
    }
}

json_out(['success' => false, 'message' => 'Unknown resource.'], 404);
