<?php
// Test session configuration
session_start();
echo "Session status: " . (session_status() === PHP_SESSION_ACTIVE ? "Active" : "Failed") . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Save path: " . session_save_path() . "\n";
echo "Writable: " . (is_writable(session_save_path()) ? "Yes" : "No") . "\n";
?>