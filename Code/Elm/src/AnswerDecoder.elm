module AnswerDecoder exposing (Answer, answerDecoder, decodeJson)

import Json.Decode as JD exposing (field, int, string)


type alias Answer =
    { id : Int
    , points : Int
    , answer : String
    , question : String
    , category : String
    }


answerDecoder : JD.Decoder Answer
answerDecoder =
    JD.map5
        Answer
        (field "id" int)
        (field "points" int)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


decodeJson : JD.Decoder (List Answer)
decodeJson =
    JD.list answerDecoder
