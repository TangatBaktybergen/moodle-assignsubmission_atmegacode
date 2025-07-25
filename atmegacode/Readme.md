# Automated ATmega Microcontroller Code Submission Precheck

This Moodle plugin provides automated validation for student submissions in ATmega microcontroller lab courses. It ensures students follow strict naming and formatting rules, verifies the presence and integrity of required `.hex` files, and assists instructors by flagging potential issues before grading.

---

## ✨ Features

### 📁 Submission Rules
- Requires exactly **one PDF file** (lab report) and **one ZIP file** (code and related files).
- Files must follow the naming pattern:  
  `LabX_studentID_semester.pdf`  
  `LabX_studentID_semester.zip`  
  *(automatically renamed if necessary)*

### 🔍 File Type and Size Validation
- Only `.pdf` and `.zip` files are accepted.
- File size limits:
  - PDF: **max 5 MB**
  - ZIP: **max 50 MB**
- ZIP content is scanned for forbidden filetypes (`.exe`, `.php`, etc.) and nested ZIP files.

### 🗂️ ZIP Structure Checks
- ZIP must not contain more than **50 folders/subfolders**.
- Every folder containing `.c` source files must include a **matching `.hex` file**.
- Missing or unmatched `.hex` files trigger detailed errors.
- Missing `Makefile` in folders also triggers an error.

### ⏱ Timestamp Comparison
- Flags `.c` or `.h` files that are **newer than their corresponding `.hex` files**, indicating recompilation is required.
- Warnings are displayed for such mismatches to aid student correction.

### 🔐 `.hex` File Verification
- Minimum number of required `.hex` files is **configurable** per assignment.
- Detects:
  - **Empty `.hex` files** (0 bytes)
  - **Uncompiled placeholder files** (containing `00000`)
  - **Missing embedded student ID** (in ASCII hex)
  - **Wrong embedded student ID** (mismatch with Moodle username)

### 👩‍🏫 Teacher-Only Cheating Flags
- If embedded student ID in `.hex` does not match Moodle ID:
  - A **warning is saved silently** and shown only to instructors.
  - Teachers see these flags during grading in the submission summary.

### 🛑 Clear Feedback with Color Coding
- Errors: Displayed with ❌ icon and block submission.
- Warnings: Displayed with ⚠️ icon but allow submission.
- Feedback includes:
  - On-screen red/green messages
  - Optional downloadable `.txt` error report

---

## ⚙️ Installation and Configuration

1. Clone or copy the plugin into your Moodle directory:  
   `moodle/mod/assign/submission/atmegacode/`

2. Complete installation via **Site administration > Notifications** in Moodle.

3. Configure assignment settings:
   - Minimum `.hex` files
   - Filename prefix (e.g. `Lab1`)
   - Semester suffix (e.g. `WS2025`)

All options are visible when enabling this plugin inside an assignment submission settings page.

---

## 🧪 Usage

- Students upload:
  - One `.pdf` report
  - One `.zip` archive of source and compiled files

- The plugin performs:
  - Immediate validation on upload
  - Blocking feedback if rules are violated
  - Success message if all checks pass

- Teachers:
  - See all submitted files
  - Receive hidden flags if student ID issues are detected

---

## 📝 License

This plugin is licensed under the [GNU General Public License v3 or later](https://www.gnu.org/licenses/gpl-3.0.html).

---

## 👤 Authors

Ivan Volosyak and Tangat Baktybergen, 2025.
