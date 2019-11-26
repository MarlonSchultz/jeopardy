![Build Status](https://github.com/MarlonSchultz/jeopardy/workflows/Node%20CI/badge.svg)

# Jeopardy 

This is a side project of mine. It utilises a RaspBerry Pi with buzzers to run a game of Jeopardy.

# How to run

The setup uses Docker to run a node server. The node server provides an api for the FrontEnd.
The BackEnd talks to the node server via Rest.

```bash
cd Docker
docker-compose up -d
```

Will start the node server and a nginx server that works as a proxy.

If there is no game.html and buzzer in the elm directory the FrontEnd has not been compiled yet.

```bash
cd Code/Elm
elm make src/Main.elm
```

If index.html is present, open the HTML file in a browser. It will try to fetch data from the nginx on localhost:8080

# How to play

You need to change the Url.elm according to oyur docker setup.

### I have an Raspberry

If you have a Raspberry and know how to connect things to the GPIO, use the python script. You need to run the docker setup on the Raspberry, or change the ip within the Python scripts.

### I dont have an Raspberry

Open buzzer.html and buzz away.

##### Buzz

Buzzing is only possible if question has been marked as open, or no other buzzer has buzzed.

```openQuestion```

Sets question as open. Buzzing is allowed, if no buzz has been registered before.

```closeQuestion```

Sets question to closed and resets the buzzer color.

```/setBuzzer/red```

```/setBuzzer/green```

```/setBuzzer/yellow```

```/setBuzzer/blue```

```/setBuzzer/none```

None resets buzzed color
