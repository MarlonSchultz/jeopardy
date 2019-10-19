module AnswerDecoder exposing (Answer(..), UnansweredConfig, answerDecoder, listOfAnswerDecoder)

import Json.Decode as JD exposing (field, string)


type alias UnansweredConfig =
    { points : String
    , answer : String
    , question : String
    , category : String
    }


type alias AnsweredConfig =
    { category : String
    }


type Answer
    = Answered AnsweredConfig
    | Unanswered UnansweredConfig


answerDecoder : JD.Decoder UnansweredConfig
answerDecoder =
    JD.map4
        UnansweredConfig
        (field "points" string)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


listOfAnswerDecoder : JD.Decoder (List UnansweredConfig)
listOfAnswerDecoder =
    JD.list answerDecoder
