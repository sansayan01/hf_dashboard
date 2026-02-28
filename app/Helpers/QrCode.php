<?php

namespace App\Helpers;

/**
 * QR Code helper that uses the qrcode-generator library by kazuhikoarase.
 * Generates QR codes entirely locally - no API calls, no network access needed.
 * Uses SVG output (no GD extension required).
 */
class QrCode
{
    /**
     * Generate a QR code and return as a base64-encoded PNG data URI string.
     * Falls back to SVG if GD is not available.
     *
     * @param string $data   The data to encode in the QR code
     * @param int    $size   Module (pixel) size (default 3)
     * @param int    $margin Quiet zone modules (default 2)
     * @return string        data:image/... ;base64,... string
     */
    public static function toBase64Png(string $data, int $size = 3, int $margin = 2): string
    {
        // Load the library (it defines the QRCode class globally)
        require_once __DIR__ . '/qrcode_lib.php';

        // Use getMinimumQRCode for automatic version selection
        $qr = \QRCode::getMinimumQRCode($data, QR_ERROR_CORRECT_LEVEL_L);

        // Try PNG via GD first
        if (function_exists('imagecreatetruecolor')) {
            $image = $qr->createImage($size, $margin);
            ob_start();
            imagepng($image);
            $pngData = ob_get_clean();
            imagedestroy($image);
            return 'data:image/png;base64,' . base64_encode($pngData);
        }

        // Fallback to SVG (no GD needed)
        return 'data:image/svg+xml;base64,' . base64_encode(self::toSvgString($qr, $size, $margin));
    }

    /**
     * Generate the QR code as an SVG string.
     */
    private static function toSvgString($qr, int $size = 3, int $margin = 2): string
    {
        $moduleCount = $qr->getModuleCount();
        $totalSize = ($moduleCount + $margin * 2) * $size;

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $totalSize . '" height="' . $totalSize . '" viewBox="0 0 ' . $totalSize . ' ' . $totalSize . '">';
        // White background
        $svg .= '<rect width="' . $totalSize . '" height="' . $totalSize . '" fill="#ffffff"/>';

        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($qr->isDark($r, $c)) {
                    $x = ($c + $margin) * $size;
                    $y = ($r + $margin) * $size;
                    $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $size . '" height="' . $size . '" fill="#000000" shape-rendering="crispEdges"/>';
                }
            }
        }

        $svg .= '</svg>';
        return $svg;
    }
}
