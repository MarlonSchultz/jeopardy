module Main exposing (Msg(..), answerRecordToElementMsg, main, update, view)

import AnswerDecoder exposing (Answer(..), UnansweredConfig, listOfAnswerDecoder)
import Browser
import Element exposing (Color, Element, centerX, centerY, column, el, fill, fromRgb255, height, layout, padding, px, row, spacing, text, width)
import Element.Background as Background
import Element.Border as Border
import Html exposing (Html)
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
    = GotJson (Result Http.Error (List UnansweredConfig))



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
        , expect =
            Http.expectJson GotJson listOfAnswerDecoder
        }
    )


filterByCategory : String -> Answer -> Bool
filterByCategory string answer =
    getCategoryFromAnswer answer == string


getCategoryFromAnswer : Answer -> String
getCategoryFromAnswer answer =
    case answer of
        Answered answered ->
            answered.category

        Unanswered unansweredConfig ->
            unansweredConfig.category


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


answerRecordToElementMsg : Answer -> Element Msg
answerRecordToElementMsg answer =
    createBox answer


createBox : Maybe Answer -> Element Msg
createBox answer =
    let
        returnVal =
            case answer of
                Answered _ ->
                    "X"

                Unanswered unanswered ->
                    unanswered.points
    in
    el
        [ Border.rounded 3
        , Background.color (fromRgb255 boxBlue)
        , width (px 100)
        , height (px 40)
        ]
        (el [ centerX, centerY ] (text returnVal))


getListOfCategoryAsListString : List Answer -> List String
getListOfCategoryAsListString list =
    let
        listOfCategories =
            List.map getCategoryFromAnswer list
    in
    List.Extra.unique listOfCategories


getCategoriesAsHeader : List Answer -> List (Element Msg)
getCategoriesAsHeader list =
    list
        |> getListOfCategoryAsListString
        |> List.map createBox


getListOfAnswersAsHtmlMsgByCategory : List Answer -> String -> List (Element Msg)
getListOfAnswersAsHtmlMsgByCategory list string =
    let
        listOfAnswers =
            List.filter (filterByCategory string) list
    in
    let
        elements =
            List.map answerRecordToElementMsg listOfAnswers
    in
    List.map
        (column [ spacing 20 ])
        [ elements ]


getListOfElementMsgFromAnswer : List Answer -> List (Element Msg)
getListOfElementMsgFromAnswer listAnswer =
    listAnswer
        |> getListOfCategoryAsListString
        |> List.concatMap (getListOfAnswersAsHtmlMsgByCategory listAnswer)



-- UPDATE


update msg model =
    case msg of
        GotJson result ->
            case result of
                Ok answer ->
                    ( Success (List.map Unanswered answer)
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
                |> el []
                |> layout []

        Loading ->
            text "Loading"
                |> el []
                |> layout []

        Success jsonDecoded ->
            column []
                [ row [ spacing 50, padding 10, width fill ] (getCategoriesAsHeader jsonDecoded)
                , getListOfElementMsgFromAnswer
                    jsonDecoded
                    |> row [ spacing 50, padding 10, width fill ]
                ]
                |> el [ centerX, centerY ]
                |> layout []



-- CSS Styles


type alias ColorRecord =
    { red : Float
    , green : Float
    , blue : Float
    , alpha : Float
    }


boxBlue =
    { red = 150
    , green = 0
    , blue = 255
    , alpha = 100
    }
