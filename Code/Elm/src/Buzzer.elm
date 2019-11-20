module Buzzer exposing (init, main)

import Browser
import Html exposing (Html, div, node, span)
import Html.Attributes exposing (class, classList, href, rel, style)


type Msg
    = Buzz


main =
    Browser.element
        { init = init
        , update = update
        , subscriptions = subscriptions
        , view = view
        }


init : () -> ( Model, Cmd Msg )
init _ =
    ( 1, Cmd.none )


type alias Model =
    Int


update : Msg -> Model -> ( Model, Cmd Msg )
update msg model =
    ( model, Cmd.none )


subscriptions : Model -> Sub Msg
subscriptions model =
    Sub.none


loadCss : String -> Html Msg
loadCss cssLink =
    node "link"
        [ rel "stylesheet"
        , href cssLink
        ]
        []


view : Model -> Html Msg
view _ =
    div []
        [ loadCss "http://localhost:8080/css/elm/jeopardy.css"
        , span [ classList [ ( "buzzer", True ), ( "red", True ) ] ] []
        ]
