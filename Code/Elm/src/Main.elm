module Main exposing (Msg(..), answerRecordToElementMsg, main, update, view)

import AnswerDecoder exposing (Answer, arrayOfAnswerDecoder)
import Browser
import Element exposing (Color, Element, centerX, centerY, column, el, fill, fromRgb255, height, layout, padding, px, row, spacing, text, width)
import Element.Background as Background
import Element.Border as Border
import Html exposing (Html)
import Html.Attributes exposing (align, style)
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


getAnswerWithCat : String -> Answer -> Bool
getAnswerWithCat string { category } =
    category == string


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


answerRecordToElementMsg : Answer -> Element msg
answerRecordToElementMsg answer =
    el
        [ Border.rounded 3
        , Background.color (fromRgb255 boxBlue)
        , width (px 100)
        , height (px 40)
        ]
        (text answer.points)


getListOfCategoryAsListString : List Answer -> List String
getListOfCategoryAsListString list =
    let
        listOfCategories =
            List.map returnOnlyCategory list
    in
    List.Extra.unique listOfCategories


returnOnlyCategory : Answer -> String
returnOnlyCategory answer =
    answer.category


getListOfAnswersAsHtmlMsgByCategory : String -> List Answer -> List (Element Msg)
getListOfAnswersAsHtmlMsgByCategory string list =
    let
        listOfAnswers =
            List.filter (getAnswerWithCat string) list
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
    let
        listString =
            getListOfCategoryAsListString listAnswer

        listToBeConcat =
            List.map (\singleString -> getListOfAnswersAsHtmlMsgByCategory singleString listAnswer) listString
    in
    List.concat listToBeConcat
        |> List.map
            (el [])



-- UPDATE


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
                |> el []
                |> layout []

        Loading ->
            text "Loading"
                |> el []
                |> layout []

        Success jsonDecoded ->
            getListOfElementMsgFromAnswer jsonDecoded
                |> row [ spacing 50, padding 10, width fill ]
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
    { red = 0
    , green = 0
    , blue = 255
    , alpha = 100
    }
