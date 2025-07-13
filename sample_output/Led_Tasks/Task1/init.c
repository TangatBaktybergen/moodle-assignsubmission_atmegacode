// @author TODO
#include <avr/io.h>
#include "init.h"

void init(void){
    DDRD &= ~(1 << DDD2);   // set PD2 as input
    PORTD |= (1 << PD2);    // enable pull-up for PD2
    DDRB |= (1 << DDB1);    // set PB1 as output
}
