// @author TODO
#define F_CPU 8000000UL
const char MtrNum[] __attribute__((__progmem__)) = "27955";
#include <avr/io.h>
#include "init.h"

int main(void){
    init(); // Initializing I/O ports (in separate file: init.c)
    while(1){
        // Turn on Red LED (PB1) when button on PD2 is pressed
        if (~PIND & (1 << PD2))
            PORTB |= (1 << PB1);
        else
            PORTB &= ~(1 << PB1);
    }
    return 0;
}
