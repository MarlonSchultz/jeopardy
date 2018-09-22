[![Build Status](https://travis-ci.org/mayflower/jeopardy.svg?branch=master)](https://travis-ci.org/mayflower/jeopardy)

# Jeopardy in PHP

This is a simple implementation of the game "Jeopardy".
It relies on PHP 7 and SQLite3.

# Install locally

Clone repository, run docker-compose build

# Adding own questions

In Var/Data is an example.db in sqlite3 format. Use it for scaffolding.
You will need a db tool for editing (i.e. DBBrowser, or DBeaver)
For this set the paramaters.yml to:
```yml
db_path: '../Var/Data/jeopardy.db'
db_type: 'sqlite3'
```

# Consider this:

Answer should be as precise as possible, to help contestants asking the correct question.

If you dont want to edit a sqlite3 file, you can use the database.json file.

Set the paramaters.yml to:
```yml
db_path: '../Var/Data/FlatFile/database.json'
db_type: 'flatfile'
```
The database to which the game connects can be set in config/parameters.yml

# Roadmap (?)

There is no such thing. This is for fun.
If you want to add features, either fork and PR, or fork and do whatever you want.

# API
```yml
host.tld/api/getAllAnswers
```

Returns Json with all Answers and coresponding questions:

```yml
host.tld/api/setQuestionOpen/{id}
```

Inserts new question/answer in gameEvents

```yml
host.tld/api/getAllGameEvents
```

Returns Json with all game events

```yml
host.tld/api/insertBuzzer/{buzzer_id}
```

Looks for the latest open answer/question in GameEvents an adds buzzer_id

```yml
host.tld/api/setQuestionWrong
```

Sets last opened question to wrong, if not yet set.

```yml
host.tld/api/setQuestionCorrect
```

Sets last opened question to correct, if not yet set.  

# Contribution

* Please stick to PSR2 coding style.
* Contributers with the power to accept MR´s and PR´s: Please use "ci" (w/o ") at the end of your commit message, otherwise Heruko wont pull the build.

