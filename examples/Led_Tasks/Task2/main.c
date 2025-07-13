// @author TODO
#define F_CPU 8000000UL
const char MtrNum[] __attribute__((__progmem__)) = "27955";
#include <avr/io.h>
#include "init.h"

int main(void){
    init(); // Initialize I/O ports (in separate file: init.c)
    uint8_t lastButtonState = 1; // Button released (pull-up)

    while(1){
        // Read button (active low: pressed = 0)
        uint8_t buttonPressed = (~PIND & (1 << PD2)) ? 1 : 0;

        // On rising edge: from not pressed to pressed
        if (buttonPressed && lastButtonState == 0) {
            PORTB ^= (1 << PB1); // Toggle Red LED
        }

        lastButtonState = buttonPressed;
    }
    return 0;
}
