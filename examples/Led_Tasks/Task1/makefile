PORT=/dev/ttyUSB_MySmartUSB
PROGRAMMER=avr911
TARGET_CPU=atmega88pa
TARGET_PROCESSOR=m88p
CFLAGS=-Wall -Os -c -mmcu=$(TARGET_CPU)
LDFLAGS=-Os -mmcu=$(TARGET_CPU)

all: main.hex
	echo program build complete

program: main.hex
	avrdude -p $(TARGET_PROCESSOR) -c $(PROGRAMMER) -P $(PORT) -U flash:w:main.hex:i

main.hex: main.elf
	avr-objcopy -O ihex main.elf main.hex

main.elf: main.o init.o
	avr-gcc $(LDFLAGS) main.o init.o -o main.elf

main.o: main.c
	avr-gcc $(CFLAGS) main.c -o main.o

init.o: init.c init.h
	avr-gcc $(CFLAGS) init.c -o init.o

clean:
	rm -f *.o *.elf *.hex *~
