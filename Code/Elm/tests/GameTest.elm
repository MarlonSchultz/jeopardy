module GameTest exposing (suite)

import AnswerDecoder exposing (Answer)
import Element exposing (el)
import Expect exposing (Expectation)
import Main exposing (..)
import Test exposing (..)


answer : Answer
answer =
    { points = "points"
    , answer = "answer"
    , question = "question"
    , category = "category"
    }


suite : Test
suite =
    describe "Answer record"
        [ test "should be transformed to element msg" <|
            \_ ->
                answerRecordToElementMsg answer
                    |> (let
                            returnValue =
                                el [] (Element.text answer.points)
                        in
                        Expect.equal returnValue
                       )
        ]
