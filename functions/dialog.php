<?php
if (isset($_SESSION['successsession'])) {
    echo ('
    <div class="alert alert-success text-white font-weight-bold alert-with-icon alert-dismissible fade show">
        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
        <i class="tim-icons icon-simple-remove"></i>
        </button>
        <span data-notify="icon" class="tim-icons icon-support-17"></span>
        <span>
        <b> Success - </b>' . htmlspecialchars($_SESSION['successsession'], ENT_QUOTES, 'UTF-8') .
        '</span>
    </div>');
}
unset($_SESSION["successsession"]);

if (isset($_SESSION['errorsession'])) {
    echo ('
    <div class="alert alert-danger text-white font-weight-bold alert-with-icon alert-dismissible fade show">
    <p class="small">
    <strong>Oh snap!</strong> - ' . htmlspecialchars($_SESSION['errorsession'], ENT_QUOTES, 'UTF-8') . '
    </p>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    ');
}
unset($_SESSION["errorsession"]);

if (isset($_SESSION['warningsession'])) {
    echo '<div class="alert alert-warning text-white font-weight-bold alert-with-icon alert-dismissible fade show">
        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
        <i class="tim-icons icon-simple-remove"></i>
        </button>
        <span data-notify="icon" class="tim-icons icon-support-17"></span>
        <span>
        <b> HOLA! - </b>' . htmlspecialchars($_SESSION['warningsession'], ENT_QUOTES, 'UTF-8') . '</span>
    </div>';
}
unset($_SESSION["warningsession"]);
