module Main exposing (Msg(..), answerRecordToHtmlRecord, main, update, view)

import AnswerDecoder exposing (Answer, arrayOfAnswerDecoder)
import Browser
import Html exposing (Attribute, Html, div, text)
import Html.Attributes exposing (style)
import Http exposing (..)
import List.Extra


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


type Msg
    = GotJson (Result Http.Error (List Answer))



-- MODEL


type Model
    = Failure String
    | Loading
    | Success (List Answer)


init : () -> ( Model, Cmd Msg )
init _ =
    ( Loading
    , Http.get
        { url = "http://localhost:8080/api/getAllAnswers"
        , expect = Http.expectJson GotJson arrayOfAnswerDecoder
        }
    )


answerRecordToHtmlRecord : Answer -> Html msg
answerRecordToHtmlRecord answer =
    div [ style "background-color" "blue" ] [ text answer.question ]


answerRecordToStringByProperty : String -> Answer -> String
answerRecordToStringByProperty string answer =
    case string of
        "answer" ->
            answer.answer

        "question" ->
            answer.question

        "points" ->
            answer.question

        "category" ->
            answer.category

        _ ->
            "Property not found in Record"


listOfCategory : List Answer -> List (Html Msg)
listOfCategory list =
    let
        listOfCategories =
            List.map returnOnlyCategory list
    in
    List.Extra.unique listOfCategories
        |> List.map text


returnOnlyCategory : Answer -> String
returnOnlyCategory answer =
    answer.category


getListOfAnswersAsStringByCategory : String -> String -> List Answer -> List String
getListOfAnswersAsStringByCategory categoryToReturn propertyToReturn list =
    let
        listOfAnswer =
            List.filter ((\cat answer -> cat == answer.category) categoryToReturn) list
    in
    List.map (answerRecordToStringByProperty propertyToReturn) listOfAnswer


getListOfAnswersAsHtmlMsgByCategory : String -> List Answer -> List (Html Msg)
getListOfAnswersAsHtmlMsgByCategory string list =
    let
        listOfAnswers =
            List.filter (getAnswerWithCat string) list
    in
    List.map answerRecordToHtmlRecord listOfAnswers


getAnswerWithCat : String -> Answer -> Bool
getAnswerWithCat string { category } =
    category == string



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
                [ div
                    [ style "color" "red"
                    , style "background-color" "blue"
                    ]
                    (List.map
                        answerRecordToHtmlRecord
                        fullText
                    )
                , div [] [ div [] (getListOfAnswersAsHtmlMsgByCategory "Akronyme" fullText) ]
                ]
