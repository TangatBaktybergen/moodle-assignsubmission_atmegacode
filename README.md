# ATmega Code Templates and Lab Solutions

This repository provides example code templates and sample solutions for ATmega-based lab exercises.  
It is intended for use with automated lab submission plugins and manual student practice in microcontroller programming courses.

---

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




