<?php
/**
 * Displays an alert with the specified type and message.
 *
 * @param string $type The type of the alert (success, error, or warning).
 * @param string $message The message to display in the alert.
 * @return void
 */
function showAlert(string $type, string $message): void
{
    $alertTypes = [
        'success' => 'success',
        'error' => 'danger',
        'warning' => 'warning'
    ];
    $alertType = $alertTypes[$type] ?? 'info';
    $alertIcon = [
        'success' => 'fa-thumbs-up',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-triangle-exclamation'
    ][$type] ?? 'fa-bug';
    ?>
    <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="fa-solid <?= $alertIcon ?>"></i></span>
        <span class="alert-text text-sm text-white"><strong><?= $type ?> - </strong><?= $message ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php
}

// Display any success, error, or warning messages stored in the session
$messages = [
    'success' => $_SESSION['successsession'] ?? null,
    'error' => $_SESSION['errorsession'] ?? null,
    'warning' => $_SESSION['warningsession'] ?? null,
];
foreach ($messages as $type => $message) {
    if ($message) {
        showAlert($type, $message);
        unset($_SESSION[$type . 'session']);
    }
}
