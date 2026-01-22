# Coupon Code System - Complete Guide

## 🎯 Overview
The coupon code system allows Super Admins to generate single-use coupon codes that provide 100% discount on registration payments. This is primarily designed for users who pay in cash to field staff.

## 📍 How to Access

### As Super Admin:
1. Log in to your dashboard
2. Look for **"Admin Tools"** section in the left sidebar
3. Click on **"Coupon Codes"**

## 🎫 Generating Coupon Codes

### Step 1: Navigate to Generation Page
- From the Coupon Codes index page, click **"Generate New Coupons"** button
- Or directly access: `http://localhost/HF/public/coupons/create`

### Step 2: Fill Out the Form
You'll need to provide:

1. **Number of Coupons** (Required)
   - Enter how many coupons you want to generate (1-100)
   - Example: Enter `5` to generate 5 coupon codes

2. **Designation Restriction** (Required)
   - Choose which role can use these coupons:
     - **RO** - Relationship Officer (₹199)
     - **RM** - Relationship Manager (₹499)
     - **BM** - Block Manager (₹999)
     - **DM** - District Manager (₹999)
     - **Any Designation** - Universal (works for all roles)

3. **Expiration Date** (Optional)
   - Leave empty for coupons that never expire
   - Or select a future date (e.g., 2026-12-31)

4. **Notes** (Optional)
   - Add any description or reference
   - Example: "Cash received from Sandip - January 2026 batch"

### Step 3: Generate
- Click **"Generate Coupon Codes"** button
- You'll be redirected to the index page with a success message
- The generated codes will be displayed

## 📋 Managing Coupons

### Viewing All Coupons
The index page shows:
- **Total Coupons** - All generated coupons
- **Unused** - Available for use
- **Used** - Already redeemed
- **Expired** - Past expiration date

### Filtering Coupons
Use the filter form to find specific coupons:
- **Status**: All / Unused / Used / Expired
- **Designation**: All / RO / RM / BM / DM / Any
- **Search Code**: Enter coupon code to search

### Deleting Coupons
- Only **unused** coupons can be deleted
- Click the red trash icon next to the coupon
- Confirm the deletion

### Exporting Coupons
- Click **"Export to CSV"** button
- Downloads a CSV file with all coupons (respects current filters)
- Useful for printing or record-keeping

## 👥 How Users Redeem Coupons

### During Registration:
1. User fills out the registration form
2. In the "Joining Donation & Payment" section, they see "Have a coupon code?"
3. Click the link to reveal the coupon input field
4. Enter the coupon code (e.g., `HF-CASH-A7K9M`)
5. Click **"Validate Coupon"**
6. If valid:
   - Success message appears
   - UPI payment section is hidden
   - Register button becomes visible
   - User can complete registration without payment screenshot

### Validation Rules:
A coupon is valid if:
- ✅ Code exists in the database
- ✅ Has not been used before
- ✅ Has not expired (if expiration date is set)
- ✅ Matches the user's designation (or is universal)

### Error Messages:
- ❌ "Invalid coupon code" - Code doesn't exist
- ❌ "This coupon has already been used" - Code was redeemed
- ❌ "This coupon has expired" - Past expiration date
- ❌ "This coupon is only valid for [designation]" - Designation mismatch

## 🔧 Coupon Code Format

All generated codes follow this pattern:
```
HF-CASH-XXXXX
```

Where `XXXXX` is a random 5-character alphanumeric string.

Examples:
- `HF-CASH-A7K9M`
- `HF-CASH-3B8N2`
- `HF-CASH-K5P9W`

## 💡 Common Use Cases

### Scenario 1: Field Staff Collected Cash
**Situation**: Your field staff collected ₹199 cash from 3 new RO candidates.

**Steps**:
1. Go to Coupon Codes → Generate New Coupons
2. Quantity: `3`
3. Designation: `RO - Relationship Officer (₹199)`
4. Expiration: Leave empty
5. Notes: `Cash from Sandip - Jan 22, 2026`
6. Click Generate
7. Share the 3 generated codes with the candidates

### Scenario 2: Pre-generate for Distribution
**Situation**: You want to create 50 coupons in advance for field distribution.

**Steps**:
1. Go to Coupon Codes → Generate New Coupons
2. Quantity: `50`
3. Designation: `RO - Relationship Officer (₹199)`
4. Expiration: `2026-12-31`
5. Notes: `Bulk generation for field use - Q1 2026`
6. Click Generate
7. Export to CSV
8. Print and distribute to field staff

### Scenario 3: Universal Coupons
**Situation**: You want coupons that work for any designation.

**Steps**:
1. Go to Coupon Codes → Generate New Coupons
2. Quantity: `10`
3. Designation: `Any Designation (Universal)`
4. Expiration: Leave empty
5. Notes: `Universal coupons for special cases`
6. Click Generate

## 🔒 Security Features

1. **Single Use**: Each coupon can only be used once
2. **Designation Lock**: Coupons can be restricted to specific roles
3. **Expiration**: Optional expiration dates prevent misuse
4. **Audit Trail**: System tracks who generated and who used each coupon
5. **Super Admin Only**: Only Super Admins can generate/manage coupons

## 📊 Tracking & Reports

### Information Tracked:
- Coupon code
- Designation restriction
- Original amount
- Generated by (Super Admin)
- Generated at (timestamp)
- Used by (User who redeemed)
- Used at (timestamp)
- Expiration date
- Notes

### Viewing Usage:
1. Go to Coupon Codes index
2. Filter by "Used" status
3. See who used each coupon and when
4. Export for reporting

## ⚠️ Important Notes

1. **Cannot Delete Used Coupons**: Once a coupon is redeemed, it cannot be deleted (for audit purposes)
2. **No Editing**: Coupons cannot be edited after generation
3. **Unique Codes**: Each code is guaranteed to be unique
4. **Payment Bypass**: Valid coupons completely bypass UPI payment verification
5. **Permanent Record**: Used coupons remain in the system permanently

## 🆘 Troubleshooting

### "Coupon code not found"
- Check if you typed the code correctly (case-sensitive)
- Verify the code exists in the system

### "Coupon already used"
- This code has been redeemed by another user
- Generate a new coupon

### "Designation mismatch"
- The coupon is restricted to a specific role
- Use a coupon that matches your designation or a universal coupon

### "Coupon expired"
- The coupon has passed its expiration date
- Request a new coupon from admin

## 📞 Support

For any issues or questions about the coupon system, contact your system administrator.

---

**Last Updated**: January 22, 2026
**Version**: 1.0
