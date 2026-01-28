$ErrorActionPreference = "Stop"
$xampp = "C:\xampp"
$data = "$xampp\mysql\data"
$backup = "$xampp\mysql\backup"
$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$dataOld = "$xampp\mysql\data_old_$timestamp"

Write-Host "Starting MySQL Repair..."

if (-not (Test-Path $data)) {
    Write-Error "Data folder not found at $data"
    exit 1
}

# 1. Rename current data folder
Write-Host "Backing up current data to $dataOld..."
Rename-Item -Path $data -NewName $dataOld

# 2. Create new data folder
Write-Host "Creating fresh data folder..."
New-Item -ItemType Directory -Path $data | Out-Null

# 3. Copy backup contents to new data
Write-Host "Restoring default files from backup..."
Copy-Item -Path "$backup\*" -Destination $data -Recurse

# 4. Copy user database (hf_database)
if (Test-Path "$dataOld\hf_database") {
    Write-Host "Restoring 'hf_database'..."
    Copy-Item -Path "$dataOld\hf_database" -Destination "$data\hf_database" -Recurse
} else {
    Write-Warning "'hf_database' not found in old data!"
}

# 5. Copy ibdata1 (Crucial for InnoDB)
# We must overwrite the 'clean' ibdata1 from backup with the 'real' one that contains our data dictionary
if (Test-Path "$dataOld\ibdata1") {
    Write-Host "Restoring ibdata1 (InnoDB Data)..."
    Copy-Item -Path "$dataOld\ibdata1" -Destination "$data\ibdata1" -Force
} else {
    Write-Error "ibdata1 not found! Cannot recover InnoDB tables."
}

Write-Host "---------------------------------------------------"
Write-Host "Repair operation completed successfully."
Write-Host "Please go to XAMPP Control Panel and click 'Start' on MySQL."
Write-Host "---------------------------------------------------"
