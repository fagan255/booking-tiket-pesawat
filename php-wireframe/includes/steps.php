<?php
/**
 * includes/steps.php
 * Komponen reusable untuk menampilkan step indicator (Cari > Detail > Penumpang > Bayar)
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
    foreach ($steps as $stepNumber => $stepLabel) {
        $isActive = ($stepNumber <= $activeStep) ? "active" : "";
        echo '<div class="step ' . $isActive . '">';
        echo '  <div class="circle">' . $stepNumber . '</div>';
        echo '  <div>' . htmlspecialchars($stepLabel) . '</div>';
        echo '</div>';
    }
    echo '</div>';
}
