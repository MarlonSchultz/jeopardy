module Game exposing (Answer, Msg(..), answerRecordToHtmlRecord, main, update, view)

import Browser
import Html exposing (Attribute, Html, div, text)
import Html.Attributes exposing (style)
import Http exposing (..)
import Json.Decode as JD exposing (Decoder, field, string)
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


type alias Answer =
    { points : String
    , answer : String
    , question : String
    , category : String
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


arrayOfAnswerDecoder : JD.Decoder (List Answer)
arrayOfAnswerDecoder =
    JD.list answerDecoder


answerRecordToHtmlRecord : Answer -> Html msg
answerRecordToHtmlRecord answer =
    div [ style "background-color" "blue" ] [ text answer.question ]


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


getHtmlListOfAnswersByCategory : String -> List Answer -> List (Html Msg)
getHtmlListOfAnswersByCategory string list =
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
                , div [] [ div [] (getHtmlListOfAnswersByCategory "Akronyme" fullText) ]
                ]
