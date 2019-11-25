module Buzzer exposing (init, main)

import Browser
import Html exposing (Html, div, h1, node, span, text)
import Html.Attributes exposing (class, classList, href, rel, style)
import Html.Events exposing (onClick)
import Http
import Url exposing (getUrl)


type Msg
    = Buzz
    | BuzzerAnswer (Result Http.Error String)
    | SelectBuzzer BuzzerColor


type BuzzerColor
    = Green
    | Red
    | Yellow
    | Blue
    | None


type alias Model =
    { buzzerColor : BuzzerColor }


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = always Sub.none
        , view = view
        }


init : () -> ( Model, Cmd Msg )
init _ =
    ( createInitialModel, Cmd.none )


createInitialModel : Model
createInitialModel =
    { buzzerColor = None }


setBuzzerColor : Model -> BuzzerColor -> Model
setBuzzerColor model newBuzzerColor =
    { model | buzzerColor = newBuzzerColor }


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Buzz ->
            ( model, buzzRequest (getColor model.buzzerColor) )

        BuzzerAnswer _ ->
            ( model, Cmd.none )

        SelectBuzzer buzzerColor ->
            ( setBuzzerColor model buzzerColor, Cmd.none )


getColor : BuzzerColor -> String
getColor buzzerColor =
    case buzzerColor of
        Green ->
            "green"

        Red ->
            "red"

        Yellow ->
            "yellow"

        Blue ->
            "blue"

        None ->
            "none"



-- SUBSCRIPTIONS


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.none



-- Requests


buzzRequest : String -> Cmd Msg
buzzRequest buzzerColor =
    Http.get
        { url = getUrl ++ "/setbuzzer/" ++ buzzerColor
        , expect =
            Http.expectString BuzzerAnswer
        }


loadCss : String -> Html Msg
loadCss cssLink =
    node "link"
        [ rel "stylesheet"
        , href cssLink
        ]
        []


view : Model -> Html Msg
view model =
    case model.buzzerColor of
        None ->
            div []
                [ loadCss (getUrl ++ "/css/elm/jeopardy.css")
                , div [ class "containerGrid" ]
                    [ h1 [] [ text "Choose your destiny" ]
                    , div []
                        [ span
                            [ classList [ ( "buzzer", True ), ( "red", True ), ( "tinySize", True ) ], onClick (SelectBuzzer Red) ]
                            []
                        , span
                            [ classList [ ( "buzzer", True ), ( "blue", True ), ( "tinySize", True ) ], onClick (SelectBuzzer Blue) ]
                            []
                        , span
                            [ classList [ ( "buzzer", True ), ( "green", True ), ( "tinySize", True ) ], onClick (SelectBuzzer Green) ]
                            []
                        , span
                            [ classList [ ( "buzzer", True ), ( "yellow", True ), ( "tinySize", True ) ], onClick (SelectBuzzer Yellow) ]
                            []
                        ]
                    ]
                ]

        _ ->
            div [ class "containerGrid" ]
                [ loadCss (getUrl ++ "/css/elm/jeopardy.css")
                , span
                    [ classList
                        [ ( "buzzer", True )
                        , ( getColor model.buzzerColor, True )
                        , ( "normalSize", True )
                        ]
                    , style "margin-top" "20vh"
                    , onClick Buzz
                    ]
                    []
                ]
