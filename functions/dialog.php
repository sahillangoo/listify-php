<?php
function showAlert($type, $message)
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
        'warning' => ' fa-triangle-exclamation'
    ][$type] ?? 'fa-bug';
    echo <<<HTML
    <div class="alert alert-$alertType alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="fa-solid $alertIcon"></i></span>
        <span class="alert-text text-sm text-white"><strong>$type - </strong>$message</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
HTML;
}

if (isset($_SESSION['successsession'])) {
    showAlert('success', $_SESSION['successsession']);
    unset($_SESSION['successsession']);
}

if (isset($_SESSION['errorsession'])) {
    showAlert('error', $_SESSION['errorsession']);
    unset($_SESSION['errorsession']);
}

if (isset($_SESSION['warningsession'])) {
    showAlert('warning', $_SESSION['warningsession']);
    unset($_SESSION['warningsession']);
}
