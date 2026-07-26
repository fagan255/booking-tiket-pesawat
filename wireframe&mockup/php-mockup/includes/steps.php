<?php
/**
 * includes/steps.php
 * Komponen reusable step indicator, versi bertema (done/active/upcoming)
 * Dipakai di halaman: flight-detail, passenger-data, payment
 */

function renderSteps(int $activeStep): void
{
    $steps = [
        1 => "Cari",
        2 => "Detail",
        3 => "Penumpang",
        4 => "Bayar",
    ];

    echo '<div class="steps">';
    $i = 0;
    $total = count($steps);
    foreach ($steps as $stepNumber => $stepLabel) {
        $i++;
        if ($stepNumber < $activeStep) {
            $state = "done";
            $inner = "&#10003;"; // checkmark
        } elseif ($stepNumber === $activeStep) {
            $state = "active";
            $inner = (string) $stepNumber;
        } else {
            $state = "";
            $inner = (string) $stepNumber;
        }

        echo '<div class="step ' . $state . '">';
        echo '  <div class="circle">' . $inner . '</div>';
        echo '  <div>' . htmlspecialchars($stepLabel) . '</div>';
        echo '</div>';

        if ($i < $total) {
            echo '<div class="connector"></div>';
        }
    }
    echo '</div>';
}
