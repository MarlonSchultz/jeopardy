module GameTest exposing (suite)

import AnswerDecoder exposing (Answer)
import Expect exposing (Expectation)
import Fuzz exposing (Fuzzer, int, list, string)
import Html exposing (div, text)
import Html.Attributes exposing (style)
import Main exposing (..)
import Test exposing (..)


answer : Answer
answer =
    { points = "10"
    , answer = "answer"
    , question = "question"
    , category = "category"
    }


suite : Test
suite =
    describe "Answer record"
        [ test "should be transformed to html msg" <|
            \_ ->
                answerRecordToHtmlRecord answer
                    |> (let
                            returnValue =
                                div [ style "background-color" "blue" ] [ text "question" ]
                        in
                        Expect.equal returnValue
                       )
        ]
