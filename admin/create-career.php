<?php
/**
 * UPHSL Admin Create Career Posting
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for creating new career postings
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in and has appropriate role (HR, Admin, or Super Admin)
if (!isLoggedIn() || (!isHR() && !isAdmin() && !isSuperAdmin())) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Create Career Posting';

$error = '';
$success = '';
$isEdit = false;
$career = null;

$pdo = getDBConnection();

// Check if this is an edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $isEdit = true;
    $careerId = (int)$_GET['edit'];
    
    // Get the career posting data
    // HR, Admins, and Super Admins can edit any posting
    $career = getCareerPostingById($careerId);
    
    if (!$career) {
        $error = 'Career posting not found or you do not have permission to edit it';
        $isEdit = false;
    } else {
        $page_title = 'Edit Career Posting';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
        $position = Validator::sanitize($_POST['position'], 'string');
        $location = Validator::sanitize($_POST['location'], 'string');
        $employmentType = Validator::sanitize($_POST['employment_type'], 'string');
        $jobDescription = $_POST['job_description']; // Rich text - sanitized on output
        $requirements = $_POST['requirements']; // Rich text - sanitized on output
        $applicationDetails = $_POST['application_details']; // Rich text - sanitized on output
        $status = Validator::sanitize($_POST['status'] ?? 'draft', 'string');
        $publishedDate = Validator::sanitize($_POST['published_date'] ?? null, 'string');
    $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';
    $careerId = isset($_POST['career_id']) ? (int)$_POST['career_id'] : 0;
    
    if (empty($position) || empty($location) || empty($employmentType) || empty($jobDescription) || empty($requirements) || empty($applicationDetails)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $pdo->beginTransaction();
            
            if ($isEdit && $careerId > 0) {
                // Update existing career posting
                // HR, Admins, and Super Admins can update any posting
                $stmt = $pdo->prepare("
                    UPDATE careers_postings 
                    SET position = ?, location = ?, employment_type = ?, job_description = ?, 
                        requirements = ?, application_details = ?, status = ?, published_at = ?, 
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?
                ");
                $stmt->execute([$position, $location, $employmentType, $jobDescription, $requirements, $applicationDetails, $status, $publishedDate, $careerId]);
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Career posting not found or you do not have permission to edit it');
                }
            } else {
                // Create new career posting
                $slug = generateUniqueCareerSlug($position);
                $stmt = $pdo->prepare("
                    INSERT INTO careers_postings (position, slug, location, employment_type, job_description, requirements, application_details, status, published_at, author_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$position, $slug, $location, $employmentType, $jobDescription, $requirements, $applicationDetails, $status, $publishedDate, $_SESSION['user_id']]);
                $careerId = $pdo->lastInsertId();
            }
            
            $pdo->commit();
            
            // Set success message and redirect
            if ($isEdit) {
                $successMsg = urlencode('Career posting updated successfully!');
            } else {
                $successMsg = urlencode('Career posting created successfully!');
            }
            
            header('Location: careers.php?success=' . $successMsg);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Failed to save career posting. Please try again.';
        }
        }
    }
}
?>

<?php 
$additional_css = '<link rel="stylesheet" href="../assets/css/editor.css">';
?>
<?php include '../app/includes/admin-header.php'; ?>

    <!-- Editor Content -->
    <div class="editor-container">
        <div class="editor-header">
            <h1 class="editor-title">
                <i class="fas fa-briefcase"></i>
                <?php echo $isEdit ? 'Edit Career Posting' : 'Create New Career Posting'; ?>
            </h1>
            <p class="editor-subtitle"><?php echo $isEdit ? 'Update the career posting details' : 'Post a new job opportunity'; ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="editor-form">
            <?php echo CSRF::field(); ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="is_edit" value="1">
                <input type="hidden" name="career_id" value="<?php echo $career['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="position" class="form-label">
                    <i class="fas fa-user-tie"></i>
                    Position (Title) <span style="color: red;">*</span>
                </label>
                <input type="text" id="position" name="position" class="form-input" 
                       value="<?php echo $isEdit ? htmlspecialchars($career['position']) : (isset($_POST['position']) ? htmlspecialchars($_POST['position']) : ''); ?>" 
                       placeholder="e.g., Software Engineer, Marketing Manager..." required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="location" class="form-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Location <span style="color: red;">*</span>
                    </label>
                    <input type="text" id="location" name="location" class="form-input" 
                           value="<?php echo $isEdit ? htmlspecialchars($career['location']) : (isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''); ?>" 
                           placeholder="e.g., Manila, Laguna, Remote..." required>
                </div>
                
                <div class="form-group">
                    <label for="employment_type" class="form-label">
                        <i class="fas fa-clock"></i>
                        Employment Type <span style="color: red;">*</span>
                    </label>
                    <select id="employment_type" name="employment_type" class="form-input" required>
                        <option value="">Select Employment Type</option>
                        <option value="Full-time" <?php echo ($isEdit && $career['employment_type'] === 'Full-time') || (isset($_POST['employment_type']) && $_POST['employment_type'] === 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                        <option value="Part-time" <?php echo ($isEdit && $career['employment_type'] === 'Part-time') || (isset($_POST['employment_type']) && $_POST['employment_type'] === 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                        <option value="Contract" <?php echo ($isEdit && $career['employment_type'] === 'Contract') || (isset($_POST['employment_type']) && $_POST['employment_type'] === 'Contract') ? 'selected' : ''; ?>>Contract</option>
                        <option value="Internship" <?php echo ($isEdit && $career['employment_type'] === 'Internship') || (isset($_POST['employment_type']) && $_POST['employment_type'] === 'Internship') ? 'selected' : ''; ?>>Internship</option>
                        <option value="Temporary" <?php echo ($isEdit && $career['employment_type'] === 'Temporary') || (isset($_POST['employment_type']) && $_POST['employment_type'] === 'Temporary') ? 'selected' : ''; ?>>Temporary</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="job_description" class="form-label">
                    <i class="fas fa-file-alt"></i>
                    Job Description <span style="color: red;">*</span>
                </label>
                <!-- Quill Editor Container -->
                <div id="job-description-editor"></div>
                <!-- Hidden textarea for form submission -->
                <textarea id="job_description" name="job_description" class="form-textarea" required><?php 
                    if ($isEdit && isset($career['job_description'])) {
                        echo html_entity_decode($career['job_description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (isset($_POST['job_description'])) {
                        echo html_entity_decode($_POST['job_description'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                ?></textarea>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 0.875rem;">
                    <i class="fas fa-info-circle"></i> Describe the role, responsibilities, and what the position entails.
                </small>
            </div>

            <div class="form-group">
                <label for="requirements" class="form-label">
                    <i class="fas fa-list-check"></i>
                    Requirements <span style="color: red;">*</span>
                </label>
                <!-- Quill Editor Container -->
                <div id="requirements-editor"></div>
                <!-- Hidden textarea for form submission -->
                <textarea id="requirements" name="requirements" class="form-textarea" required><?php 
                    if ($isEdit && isset($career['requirements'])) {
                        echo html_entity_decode($career['requirements'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (isset($_POST['requirements'])) {
                        echo html_entity_decode($_POST['requirements'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                ?></textarea>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 0.875rem;">
                    <i class="fas fa-info-circle"></i> List the qualifications, skills, and experience required for this position.
                </small>
            </div>

            <div class="form-group">
                <label for="application_details" class="form-label">
                    <i class="fas fa-envelope"></i>
                    Application Details <span style="color: red;">*</span>
                </label>
                <!-- Quill Editor Container -->
                <div id="application-details-editor"></div>
                <!-- Hidden textarea for form submission -->
                <textarea id="application_details" name="application_details" class="form-textarea" required><?php 
                    if ($isEdit && isset($career['application_details'])) {
                        echo html_entity_decode($career['application_details'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (isset($_POST['application_details'])) {
                        echo html_entity_decode($_POST['application_details'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                ?></textarea>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 0.875rem;">
                    <i class="fas fa-info-circle"></i> Provide information on how to apply (email, application process, deadline, etc.).
                </small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-eye"></i>
                        Status
                    </label>
                    <select id="status" name="status" class="form-input">
                        <?php 
                        $defaultStatus = $isEdit ? ($career['status'] ?? 'draft') : 'draft';
                        $selectedStatus = isset($_POST['status']) ? $_POST['status'] : ($isEdit ? ($career['status'] ?? 'draft') : $defaultStatus);
                        ?>
                        <option value="draft" <?php echo $selectedStatus === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $selectedStatus === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="archived" <?php echo $selectedStatus === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="published_date" class="form-label">
                        <i class="fas fa-calendar"></i>
                        Publish Date
                    </label>
                    <input type="datetime-local" id="published_date" name="published_date" class="form-input"
                           value="<?php 
                               if ($isEdit && $career['published_at']) {
                                   echo date('Y-m-d\TH:i', strtotime($career['published_at']));
                               } elseif (isset($_POST['published_date'])) {
                                   echo htmlspecialchars($_POST['published_date']);
                               } else {
                                   echo date('Y-m-d\TH:i');
                               }
                           ?>">
                </div>
            </div>

            <div class="form-actions">
                <a href="careers.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $isEdit ? 'Update Posting' : 'Create Posting'; ?>
                </button>
            </div>
        </form>
    </div>

    <script src="../assets/js/script.js"></script>
    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        #job-description-editor,
        #requirements-editor,
        #application-details-editor {
            height: 300px;
            margin-bottom: 20px;
        }
        .ql-editor {
            min-height: 250px;
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 14px;
        }
        #job_description,
        #requirements,
        #application_details {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Editors
            const editors = {
                'job-description': {
                    editor: new Quill('#job-description-editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['link', 'blockquote'],
                                ['clean']
                            ]
                        },
                        placeholder: 'Describe the job role and responsibilities...'
                    }),
                    textarea: document.getElementById('job_description')
                },
                'requirements': {
                    editor: new Quill('#requirements-editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['link', 'blockquote'],
                                ['clean']
                            ]
                        },
                        placeholder: 'List the requirements and qualifications...'
                    }),
                    textarea: document.getElementById('requirements')
                },
                'application-details': {
                    editor: new Quill('#application-details-editor', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{ 'header': [1, 2, 3, false] }],
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['link', 'blockquote'],
                                ['clean']
                        ]
                        },
                        placeholder: 'Provide application instructions...'
                    }),
                    textarea: document.getElementById('application_details')
                }
            };
            
            // Load initial content
            Object.keys(editors).forEach(key => {
                const config = editors[key];
                if (config.textarea && config.textarea.value) {
                    config.editor.root.innerHTML = config.textarea.value;
                }
            });
            
            // Sync editors to textareas on form submit
            const form = document.querySelector('.editor-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    Object.keys(editors).forEach(key => {
                        const config = editors[key];
                        config.textarea.value = config.editor.root.innerHTML;
                    });
                });
            }
            
            // Also sync on content change
            Object.keys(editors).forEach(key => {
                const config = editors[key];
                config.editor.on('text-change', function() {
                    config.textarea.value = config.editor.root.innerHTML;
                });
            });
        });
    </script>

<?php include '../app/includes/admin-footer.php'; ?>

