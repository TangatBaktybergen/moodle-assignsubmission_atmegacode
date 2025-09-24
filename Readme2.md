# ATmega Microcontroller Code Submission Plugin for Moodle

This Moodle plugin provides a secure and automated validation system for student submissions in ATmega-based microcontroller lab courses. It supports file naming rules, integrity checks for compiled `.hex` files, timestamp validation, and silent warning flags for instructors — all designed to improve submission quality and reduce grading errors.

---

## ✨ Key Features

### 📁 Structured Submission Rules
- Requires exactly:
  - **1 PDF** lab report
  - **1 ZIP** archive of code + compiled files
- Files are automatically renamed to:
LabX_studentID_semester.pdf
LabX_studentID_semester.zip
- Prefix and semester are configurable per assignment.

### 🔍 File and Format Validation
- Enforces accepted file types (`.zip`, `.pdf`) and size limits:
- PDF: max **5 MB**
- ZIP: max **50 MB**
- ZIP file validation includes:
- No more than **50 folders**
- **No nested ZIPs**
- No disallowed types (`.exe`, `.php`, `.py`, etc.)
- File timestamp checks

### 🧪 .hex File Validation
- Required `.hex` file count is configurable (e.g., 3+).
- Checks for:
- **Empty** `.hex` files (0 bytes)
- **Uncompiled** files (containing `00000`)
- **Missing** embedded student ID (ASCII hex)
- **Wrong** student ID (suspicious reuse or mismatch)

### 🕒 Timestamp Integrity
- Compares `.c` / `.h` source file timestamps against compiled `.hex` files
- Detects and warns about mismatches
- Flags outdated `.hex` builds for manual attention

### ☠️ Cheating & Integrity Warnings (Instructor-Only)
- Any `.hex` file with a mismatched or missing student ID is silently flagged
- Stored in a dedicated table `atmega_flags` and shown only to teachers during grading
- Warnings are grouped as:
- **☠️ Cheating Suspicions**
- **⚠️ Minor Issues** (timestamps, no Makefile, etc.)

### 🛠 Developer & Admin Features
- Assignment-level settings:
- Default prefix (e.g., `Lab1`)
- Default semester (e.g., `WS2025`)
- Required minimum number of `.hex` files
- Color-coded student feedback (✅ success, ❌ blocking errors, ⚠️ warnings)
- Generates `.txt` validation report per submission
- Automatically renames uploaded files

---

## 📦 Backup & Restore

This plugin fully supports Moodle backup/restore for:
- Student submission records (`assignsubmission_atmegacode`)
- Attached ZIP and PDF files in the `submission_files` filearea

> ❗ The table `atmega_flags`, which stores teacher-only warning flags, is **excluded from backups**. These flags are auto-generated from submitted files and not essential for data integrity.

---

## 🧪 Usage

### For Students
- Upload exactly:
- 1 `.pdf` report
- 1 `.zip` archive of source code and `.hex` files
- View inline feedback immediately after upload
- Fix and re-submit if validation fails

### For Teachers
- Configure requirements when setting up the assignment
- View all uploaded files + any validation warnings
- Detect mismatches, cheating risks, and folder issues at a glance

---

## ⚙️ Installation

1. Place the plugin in the Moodle directory:
mod/assign/submission/atmegacode/

2. Go to **Site administration > Notifications** to complete installation.

3. Enable the plugin inside each assignment under:
**Submission Types > MCU code submission (ATmega)**

---

## 📝 License

This plugin is licensed under the [GNU General Public License v3 or later](https://www.gnu.org/licenses/gpl-3.0.html).

---

## 👤 Authors

Developed by Ivan Volosyak and Tangat Baktybergen (2025)  
For academic use at Hochschule Rhein-Waal and beyond.

