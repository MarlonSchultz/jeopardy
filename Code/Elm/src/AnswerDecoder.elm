module AnswerDecoder exposing (Answer, answerDecoder, arrayOfAnswerDecoder)

import Json.Decode as JD exposing (field, string)


type alias Answer =
    { points : String
    , answer : String
    , question : String
    , category : String
    }


answerDecoder : JD.Decoder Answer
answerDecoder =
    JD.map4
        Answer
        (field "points" string)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


arrayOfAnswerDecoder : JD.Decoder (List Answer)
arrayOfAnswerDecoder =
    JD.list answerDecoder
