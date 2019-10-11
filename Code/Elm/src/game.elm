module GAME exposing (Msg(..), main, update, view)

import Array exposing (Array)
import Browser
import Html exposing (Html, div, pre, text)
import Http exposing (..)
import Json.Decode as JD exposing (Decoder, array, field, int, string)


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (Array Answer))



-- MODEL


type Model
    = Failure String
    | Loading
    | Success (Array Answer)


type alias Answer =
    { points : String
    , answer : String
    , question : String
    , category : String
    }


type alias Category =
    { category : String
    , answerList : List Answer
    }


init : () -> ( Model, Cmd Msg )
init _ =
    ( Loading
    , Http.get
        { url = "http://localhost:8080/api/getAllAnswers"
        , expect = Http.expectJson GotJson arrayOfAnswerDecoder
        }
    )


answerDecoder : JD.Decoder Answer
answerDecoder =
    JD.map4
        Answer
        (field "points" string)
        (field "answer" string)
        (field "question" string)
        (field "category" string)


arrayOfAnswerDecoder : JD.Decoder (Array Answer)
arrayOfAnswerDecoder =
    JD.array answerDecoder


arrayOfAnswersToAnswerRecord : Int -> Array Answer -> Maybe Answer
arrayOfAnswersToAnswerRecord int array =
    Array.get int array


answerToString : String -> Maybe Answer -> String
answerToString whatShouldIReturn maybe =
    case maybe of
        Nothing ->
            "String empty"

        Just a ->
            case whatShouldIReturn of
                "answer" ->
                    a.answer

                "question" ->
                    a.question

                "points" ->
                    a.points

                _ ->
                    "Record not present"



-- UPDATE


errorToString : Http.Error -> String
errorToString error =
    case error of
        BadUrl url ->
            "The URL " ++ url ++ " was invalid"

        Timeout ->
            "Unable to reach the server, try again"

        NetworkError ->
            "Unable to reach the server, check your network connection"

        BadStatus 500 ->
            "The server had a problem, try again later"

        BadStatus 400 ->
            "Verify your information and try again"

        BadStatus _ ->
            "Unknown error"

        BadBody errorMessage ->
            errorMessage


update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok fullText ->
                    ( Success fullText
                    , Cmd.none
                    )

                Err err ->
                    ( Failure (errorToString err), Cmd.none )



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.none



-- VIEW


view : Model -> Html Msg
view model =
    case model of
        Failure err ->
            text err

        Loading ->
            text "Loading..."

        Success fullText ->
            div []
                [ let
                    curry =
                        arrayOfAnswersToAnswerRecord 0
                  in
                  curry fullText
                    |> answerToString "answer"
                    |> text
                ]
