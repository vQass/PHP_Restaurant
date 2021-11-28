<?php
function ErrorMessageGenerator($rawMessage)
{
    $message = '<div class="alert alert-danger alert-dismissible fade show transparent" role="alert">'
        . $rawMessage .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';

    return $message;
}
function SuccessMessageGenerator($rawMessage)
{
    $message = '<div class="alert alert-success alert-dismissible fade show transparent" role="alert">'
        . $rawMessage .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

    return $message;
}
