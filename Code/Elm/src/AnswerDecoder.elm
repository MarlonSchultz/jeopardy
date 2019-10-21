module AnswerDecoder exposing (Answer(..), UnansweredConfig, answerDecoder, listOfAnswerDecoder)

import Html exposing (text)
import Json.Decode as JD exposing (field, string)


type alias UnansweredConfig =
    { id : String
    , points : String
    , answer : String
    , question : String
    , category : String
    }


type Answer
    = Answered UnansweredConfig
    | Unanswered UnansweredConfig


answerDecoder : JD.Decoder UnansweredConfig
answerDecoder =
    JD.map5
        UnansweredConfig
        (field "id" string)
        (field "points" string)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


listOfAnswerDecoder : JD.Decoder (List UnansweredConfig)
listOfAnswerDecoder =
    JD.list answerDecoder
