<?php

use Illuminate\Support\Facades\Route;
use App\Models\UserProfile;

// Diagnostic route for profile picture issues
Route::get('/diag/profile-pictures', function () {
    $html = '<div style="font-family: sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto;">';
    $html .= '<h1 style="color: #3C50E0;">Profile Picture Diagnostic</h1>';

    // Get recent profiles with pictures
    $profiles = UserProfile::whereNotNull('profile_picture')
        ->with('user')
        ->latest()
        ->take(10)
        ->get();

    $html .= '<h2>Recent Profiles with Pictures (Last 10)</h2>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
    $html .= '<thead><tr style="background: #f1f5f9;">';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">User</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">DB Path</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">File Exists?</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Checked Paths</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">URL</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Preview</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($profiles as $profile) {
        $path = $profile->profile_picture;
        $possiblePaths = [
            'storage_path' => storage_path('app/public/' . $path),
            'base_path' => base_path('storage/app/public/' . $path),
            'public_path' => public_path('storage/' . $path),
            'external' => base_path('../storage/app/public/' . $path),
        ];

        $foundPath = null;
        $existsChecks = [];
        foreach ($possiblePaths as $label => $fullPath) {
            $exists = file_exists($fullPath) && !is_dir($fullPath);
            $existsChecks[] = "<b>$label:</b> " . ($exists ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . "<br><small style='color: #666;'>$fullPath</small>";
            if ($exists && !$foundPath) {
                $foundPath = $fullPath;
            }
        }

        $url = $profile->getProfilePictureUrl();
        $urlDisplay = $url ? "<a href='$url' target='_blank' style='color: #3C50E0;'>View</a>" : '<span style="color: red;">NULL</span>';

        $preview = $url ? "<img src='$url' style='width: 50px; height: 50px; object-fit: cover; border-radius: 8px;' onerror='this.style.border=\"2px solid red\"'>" : '❌';

        $html .= '<tr>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($profile->full_name ?? 'N/A') . '<br><small>' . ($profile->user->employee_id ?? 'N/A') . '</small></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><code>' . $path . '</code></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($foundPath ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd; font-size: 11px;">' . implode('<br><br>', $existsChecks) . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . $urlDisplay . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd; text-align: center;">' . $preview . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Storage directory permissions
    $html .= '<h2>Storage Directory Information</h2>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
    $html .= '<thead><tr style="background: #f1f5f9;">';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Path Type</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Full Path</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Exists?</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Writable?</th>';
    $html .= '<th style="padding: 10px; border: 1px solid #ddd;">Files Count</th>';
    $html .= '</tr></thead><tbody>';

    $storageDirs = [
        'storage_path' => storage_path('app/public/profile_pictures'),
        'base_path' => base_path('storage/app/public/profile_pictures'),
        'public_path' => public_path('storage/profile_pictures'),
        'external' => base_path('../storage/app/public/profile_pictures'),
    ];

    foreach ($storageDirs as $label => $dir) {
        $exists = is_dir($dir);
        $writable = $exists && is_writable($dir);
        $count = 0;
        if ($exists) {
            $files = glob($dir . '/*');
            $count = count(array_filter($files, 'is_file'));
        }

        $html .= '<tr>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><b>' . $label . '</b></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;"><code>' . $dir . '</code></td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($exists ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . ($writable ? '<span style="color: green;">✓ YES</span>' : '<span style="color: red;">✗ NO</span>') . '</td>';
        $html .= '<td style="padding: 10px; border: 1px solid #ddd;">' . $count . ' files</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Environment info
    $html .= '<h2>Environment Information</h2>';
    $html .= '<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>APP_ENV</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . env('APP_ENV') . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>APP_URL</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . env('APP_URL') . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Base Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . base_path() . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Storage Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . storage_path() . '</td></tr>';
    $html .= '<tr><td style="padding: 10px; border: 1px solid #ddd;"><b>Public Path</b></td><td style="padding: 10px; border: 1px solid #ddd;">' . public_path() . '</td></tr>';
    $html .= '</table>';

    $html .= '<div style="margin-top: 40px; padding: 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;">';
    $html .= '<h3 style="color: #166534; margin-top: 0;">Recommendations</h3>';
    $html .= '<ol style="color: #166534;">';
    $html .= '<li>If files exist locally but not on live server, you need to upload the <code>storage/app/public/profile_pictures</code> folder to your live server.</li>';
    $html .= '<li>Make sure the storage directory has proper write permissions (755 or 775).</li>';
    $html .= '<li>If using FTP/SFTP, ensure you upload the storage folder after each deployment.</li>';
    $html .= '<li>Consider setting up automated deployment or using rsync to sync files.</li>';
    $html .= '</ol>';
    $html .= '</div>';

    $html .= '</div>';

    return $html;
})->middleware('auth');
