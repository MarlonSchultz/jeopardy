import time
import RPi.GPIO as GPIO
import urllib3


http = urllib3.PoolManager()

# RPi.GPIO Layout verwenden (wie Pin-Nummern)
GPIO.setmode(GPIO.BOARD)


GPIO.setup(11, GPIO.IN)
GPIO.setup(12, GPIO.IN)
GPIO.setup(13, GPIO.IN)
GPIO.setup(16, GPIO.IN)

	
def buzzer_called(colour):
	print('Es wurde gedrückt' + colour)
	try:
		request = http.request('GET', 'jeophpardy.remote:8080/buzzer/'+colour)
	except:
		print('Request Failed')
	time.sleep(4)
	
# Dauersschleife
while 1:

	if GPIO.input(11) == GPIO.HIGH:
		buzzer_called('1')
		#print('Blau is an')
	
	if GPIO.input(13) == GPIO.HIGH:
		buzzer_called('2')
		#print('Grün is an')
        
	if GPIO.input(12) == GPIO.HIGH:
		buzzer_called('3')
		#print('Gelb is an')

	if GPIO.input(16) == GPIO.HIGH:
		buzzer_called('4')
		#print('Rot is an')
