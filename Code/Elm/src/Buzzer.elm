module Buzzer exposing (init, main)

import Browser
import Html exposing (Html, div, h1, node, span, text)
import Html.Attributes exposing (class, classList, href, rel, style)
import Html.Events exposing (onClick)
import Http
import HttpHandler exposing (errorToString)
import Time
import Url exposing (getUrl)


type Msg
    = Buzz
    | BuzzerAnswer (Result Http.Error String)
    | SelectBuzzer BuzzerColor
    | HideShowMessage Time.Posix


type BuzzerColor
    = Green
    | Red
    | Yellow
    | Blue
    | None


type BuzzerMessage
    = NotBuzzed
    | Buzzed String


type alias Model =
    { buzzerColor : BuzzerColor
    , buzzerMessage : BuzzerMessage
    , showBuzzerMessage : Bool
    }


timeToShowMessage : Float
timeToShowMessage =
    2


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


init : () -> ( Model, Cmd Msg )
init _ =
    ( createInitialModel, Cmd.none )


createInitialModel : Model
createInitialModel =
    { buzzerColor = None
    , buzzerMessage = NotBuzzed
    , showBuzzerMessage = False
    }


setBuzzerColor : Model -> BuzzerColor -> Model
setBuzzerColor model newBuzzerColor =
    { model | buzzerColor = newBuzzerColor }


setBuzzerBuzzedMessage : Model -> BuzzerMessage -> Model
setBuzzerBuzzedMessage model newBuzzerMessage =
    { model | buzzerMessage = newBuzzerMessage }


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    case msg of
        Buzz ->
            ( model, buzzRequest (getColor model.buzzerColor) )

        BuzzerAnswer httpResult ->
            case httpResult of
                Ok _ ->
                    ( { model | showBuzzerMessage = True, buzzerMessage = Buzzed "Mewp!" }, Cmd.none )

                Err httpError ->
                    ( { model | showBuzzerMessage = True, buzzerMessage = Buzzed (errorToString httpError) }, Cmd.none )

        SelectBuzzer buzzerColor ->
            ( setBuzzerColor model buzzerColor, Cmd.none )

        HideShowMessage _ ->
            ( { model | showBuzzerMessage = False, buzzerMessage = NotBuzzed }, Cmd.none )


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
    case model.buzzerMessage of
        Buzzed _ ->
            Time.every 2000 HideShowMessage

        NotBuzzed ->
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


getBuzzerStatus : Model -> Html Msg
getBuzzerStatus model =
    case model.buzzerMessage of
        Buzzed string ->
            h1 [] [ text string ]

        _ ->
            h1 [] [ text "Touch it!" ]


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
                , getBuzzerStatus model
                , span
                    [ classList
                        [ ( "buzzer", True )
                        , ( getColor model.buzzerColor, True )
                        , ( "normalSize", True )
                        ]
                    , onClick Buzz
                    ]
                    []
                ]
