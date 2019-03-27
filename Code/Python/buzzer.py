#!/usr/bin/env python3
import time
import RPi.GPIO as GPIO
import urllib3

mapping = { 11: 1, 12: 3, 13: 2, 16: 4 }
lastStamp = 0

# RPi.GPIO Layout verwenden (wie Pin-Nummern)
GPIO.setmode(GPIO.BOARD)

def buzzer_pressed(channel):
    global lastStamp

    if time.time() - lastStamp < 5:
        print('Ignore button press %d' % mapping[channel])
        return

    url = "/api/insertBuzzer/%d" % mapping[channel]
    lastStamp = time.time()

    try:
        print("Sending request to %s" % url)
        pool = urllib3.HTTPConnectionPool('192.168.1.20', port=8080, maxsize=1)
        pool.urlopen('GET', url, retries=1)
        pool.close()
    except:
        print('Request failed')

for pin in mapping.keys():
    GPIO.setup(pin, GPIO.IN,pull_up_down=GPIO.PUD_DOWN)
    GPIO.add_event_detect(pin, GPIO.RISING, callback=buzzer_pressed)

try:
    while True:
        time.sleep(3600)
except KeyboardInterrupt:
    GPIO.cleanup()
