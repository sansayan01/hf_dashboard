# ID Card Format Selection Feature

## Overview
Added the ability to choose between **PNG**, **PDF**, and **JPG** formats for both single and bulk ID card generation. JPG format is specifically optimized for **Canva compatibility** and easy editing.

## Changes Made

### 1. Backend Updates (UserController.php)
- **idCard()**: Now accepts a `format` parameter (png/pdf/jpg) and passes it to the view
- **bulkDownloadIdCards()**: Accepts format parameter for bulk downloads
- **printAllIdCards()**: Accepts format parameter for printable ID cards

### 2. Single ID Card View (id_card.blade.php)
- Added format selection dropdown with 3 options:
  - **PNG Image** (default): High-resolution raster image
  - **PDF Document**: Portable document format for printing
  - **JPG (Canva Compatible)**: JPEG format optimized for Canva import and editing
- Enhanced JavaScript with 3 download functions:
  - `downloadAsPNG()`: Uses html2canvas at 4x scale
  - `downloadAsPDF()`: Uses jsPDF library with proper CR80 dimensions
  - `downloadAsJPG()`: Uses html2canvas with 95% quality JPEG compression
- Added jsPDF library CDN for PDF generation

### 3. User Profile Page (show.blade.php)
- Replaced simple "ID Card" button with dropdown menu
- Format options available:
  - PNG Image (with image icon)
  - PDF Document (with document icon)
  - JPG (Canva Compatible) (with image icon)
- Added JavaScript for dropdown toggle functionality
- Dropdown closes when clicking outside

## How to Use

### Single ID Card
1. Navigate to any user's profile page
2. Click the "ID Card" button (Super Admin only)
3. Select your preferred format from the dropdown:
   - **PNG**: Best for digital sharing and web use
   - **PDF**: Best for printing and official documents
   - **JPG**: Best for importing into Canva for editing and customization
4. The ID card will open in a new tab with the selected format pre-selected
5. Click "Download ID Card" to save the file

### Importing JPG into Canva
1. Download the ID card in JPG format
2. Go to Canva.com and create a new design
3. Upload the JPG file to Canva
4. The ID card will be fully editable - you can:
   - Change colors and backgrounds
   - Add or modify text
   - Apply filters and effects
   - Resize and reposition elements
   - Export in various formats

### Bulk ID Cards
- Access bulk download via the "Team Members" page
- Format parameter can be passed via URL: `?format=png|pdf|jpg`
- Same format selection will be available in the bulk view

## Technical Details

### PDF Generation
- Uses jsPDF library (v2.5.1)
- Dimensions: 85.6mm x 128.4mm (vertical CR80 aspect ratio)
- High-resolution canvas (4x scale) converted to PDF

### JPG Generation
- Uses html2canvas library
- 4x scale for high resolution (1280x1920px)
- 95% quality compression for optimal file size
- Removes box shadows for clean output
- **Fully compatible with Canva** for editing

### PNG Generation
- Uses html2canvas library
- 4x scale for high resolution (1280x1920px)
- Lossless compression
- Removes box shadows for clean output

## Browser Compatibility
- PNG: All modern browsers
- PDF: All modern browsers (requires jsPDF)
- JPG: All modern browsers

## File Naming Convention
All downloaded files follow the pattern: `ID_Card_{EMPLOYEE_ID}.{extension}`
Examples:
- `ID_Card_HFDM000001.png`
- `ID_Card_HFDM000001.pdf`
- `ID_Card_HFDM000001.jpg`

## Why JPG for Canva?
- **Universal Support**: JPG is universally supported by Canva
- **Editable**: Can be imported as an image layer and edited
- **Smaller File Size**: 95% quality provides excellent visual quality with smaller file sizes
- **Fast Upload**: Smaller files upload faster to Canva
- **Compatibility**: Works seamlessly with Canva's editing tools

