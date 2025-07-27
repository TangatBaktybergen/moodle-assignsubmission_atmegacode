# Automated ATmega Lab Evaluation Toolkit

This repository provides example code templates and sample solutions for ATmega-based lab exercises, intended for use with automated lab submission plugins or manual student practice in microcontroller programming courses. It also includes a Moodle plugin for ATmega microcontroller lab submissions that checks file naming conventions and ensures all required .hex files are included in the submission.

---
## Features

- **Submission Rules**
  - Only one PDF and one ZIP file per submission.
  - Both filenames must match the required pattern:<br>
    `LabX_studentID_semester.pdf` and `LabX_studentID_semester.zip`.

- **File Type and Size Validation**
  - Only `.pdf` and `.zip` files are accepted (max 1MB each).
  - ZIP archives are checked for forbidden files (such as `.exe`, scripts, and nested ZIPs).

- **ZIP Structure Checks**
  - **Each folder containing `.c` or `.h` source files must also contain at least one `.hex` file.**
    - Folders missing a `.hex` file trigger a clear error message:<br>
      <code>Folder &lt;folder&gt; contains no hex file. Please recompile or amend your submission.</code>

- **.hex File Verification**
  - Enforces a minimum number of `.hex` files per assignment (configurable).
  - Checks for empty `.hex` files.
  - Detects "uncompiled" `.hex` files (containing placeholder content).
  - Verifies that the student ID is present (in ASCII hex) inside each `.hex` file.

- **Timestamp Comparison**
  - Flags if `.c` or `.h` source files are newer than corresponding `.hex` files, indicating missing re-compilation.

- **Teacher-Only Flags**
  - Student ID mismatches and potential cheating are flagged and visible only to teachers.

- **Clear, Color-Coded Error Messages**
  - All feedback is displayed to students with clear explanations and color highlights for easy correction.

---

_This plugin streamlines ATmega lab submissions, automates checks, and improves feedback for both students and instructors._

## Folder Structure

```
/
├── atmegacode/               # Moodle plugin code
│   ├── lang/en
│      └── assignsubmission_atmegacode.php
│   ├── db
│      └── upgrade.php
│      └── install.xml
│      └── access.php
│   ├── backup
│      ├── moodle2
│         └── restore_assignsubmission_atmegacode_subplugin.class.php
│         └── backup_assignsubmission_atmegacode_subplugin.class.php
│   ├── classes
│      ├── privacy
│         └── provider
│   ├── version.php
│   ├── settings.php
│   ├── lib.php
│   ├── locallib.php
│   ├── Readme.md
├── examples/Led_tasks # Test files for ATmegacode submission plugin
│   ├── Task1    #Test case: .c source file is newer then compiled .hex file
│   ├── Task2    #Test case: .hex file is missing in the folder
│
├── sample_output/ # Pre-check output warnings from Led_tasks files tested
│ └── Precheck_output.png # Snapshot of plugin check of test files. Warnings are showed.
│ └──Led_tasks    # correct folder without errors.
│
└── README.md
```

## How to Use

- **Templates:**  
  Use the code templates in `Task1/` and `Task2/` as a starting point for your own ATmega lab projects.
- **Sample Solutions:**  
  The `sample_solutions/` folder contains correct code for reference or plugin demonstration.  
  These solutions are provided as source only, without compiled `.hex` files.

---

## File Descriptions

- `main.c` – Main program file for the task
- `init.c`, `init.h` – Port and hardware initialization code
- `Makefile` – Makefile for building the project using `avr-gcc`

---

## Authors

- Ivan Volosyak
- Tangat Baktybergen

---

## License

GNU GPL v3 or later

---

*Developed for ATmega microcontroller programming coursework and automated lab submission testing at Hochschule Rhein-Waal.*




