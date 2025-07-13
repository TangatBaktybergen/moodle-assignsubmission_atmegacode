# ATmega Code Templates and Lab Solutions

This repository provides example code templates and sample solutions for ATmega-based lab exercises.  
It is intended for use with automated lab submission plugins and manual student practice in microcontroller programming courses.

---

## Folder Structure

/
├── Task1/ # Example: Turn on Red LED on button press
│ ├── main.c
│ ├── init.c
│ ├── init.h
│ └── Makefile
│
├── Task2/ # Example: Toggle Red LED on each button press
│ ├── main.c
│ ├── init.c
│ ├── init.h
│ └── Makefile
│
├── sample_solutions/ # Sample solutions for demonstration
│ ├── Task1/ # (correct code, not compiled, no .hex files)
│ │ ├── main.c
│ │ ├── init.c
│ │ ├── init.h
│ │ └── Makefile
│ └── Task2/
│ ├── main.c
│ ├── init.c
│ ├── init.h
│ └── Makefile
│
└── README.md

---

## How to Use

- **Templates:**  
  Use the code templates in `Task1/` and `Task2/` as a starting point for your own ATmega lab projects.
- **Sample Solutions:**  
  The `sample_solutions/` folder contains correct code for reference or plugin demonstration.  
  These solutions are provided as source only, without compiled `.hex` files.

---

## Example Tasks

- **Task 1:**  
  Turn on the Red LED (PB1) while a button on PD2 is pressed.

- **Task 2:**  
  Toggle the Red LED (PB1) on each press of the button on PD2.

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




