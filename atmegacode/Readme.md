# Automated ATmega Microcontroller Code Submission Precheck

This Moodle plugin provides automated pre-checks for student submissions in ATmega microcontroller lab courses. It ensures students adhere to naming conventions, submit all required compiled `.hex` files, and helps instructors by flagging potential issues before grading.

---

## Features

### Submission Rules
- Exactly **one PDF file** (lab report) and **one ZIP file** (compiled code and optional explanatory videos) per submission.

### File Type and Size Validation
- Only `.pdf` (max 5 MB) and `.zip` (max 50 MB) files are accepted.
- Files are automatically renamed to follow the pattern:
LabX_studentID_semester.pdf
LabX_studentID_semester.zip

- ZIP archives are checked to ensure no nested subfolders are present.

### ZIP Structure Checks
- Every folder containing `.c` source files (usually `main.c`) **must have a corresponding `.hex` file**.
- Folders missing `.hex` files trigger an error like:
Folder <folder> contains no hex file. Please recompile and amend your submission.


### Timestamp Comparison
- Flags if `.c` or `.h` source files are newer than their corresponding `.hex` files, indicating recompilation is needed.

### `.hex` File Verification
- Enforces a configurable minimum number of `.hex` files per assignment.
- Detects empty `.hex` files.
- Verifies that student ID is embedded in ASCII hex inside each `.hex` file.
- Detects placeholder `.hex` files containing `"00000"` as a student ID (uncompiled).

### Teacher-Only Flags
- Submissions with mismatched student IDs (compared to Moodle user ID) are flagged for potential cheating.
- Flags are only visible to teachers.

### Clear, Color-Coded Error Messages
- Students receive clear feedback with color highlights to guide corrections before resubmission.

---

## Installation and Configuration

1. Upload the plugin to `assign/submission/atmegacode` in your Moodle installation.
2. Complete installation via *Site administration > Notifications*.
3. Configure plugin settings globally or per assignment, including minimum required `.hex` files, filename prefix, and semester suffix.

---

## Usage

- Students upload one PDF lab report and one ZIP archive containing source and compiled code.
- The plugin validates submissions immediately, providing feedback and requiring corrections if necessary.
- Teachers access flagged warnings during grading to identify problematic submissions.

---

## License

This plugin is licensed under the GNU GPL v3 or later.

---

## Authors

Ivan Volosyak and Tangat Baktybergen, 2025.

