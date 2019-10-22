module AnswerDecoder exposing (Answer, answerDecoder, decodeJson)

import Json.Decode as JD exposing (field, string)


type alias Answer =
    { id : String
    , points : String
    , answer : String
    , question : String
    , category : String
    }


answerDecoder : JD.Decoder Answer
answerDecoder =
    JD.map5
        Answer
        (field "id" string)
        (field "points" string)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


decodeJson : JD.Decoder (List Answer)
decodeJson =
    JD.list answerDecoder
